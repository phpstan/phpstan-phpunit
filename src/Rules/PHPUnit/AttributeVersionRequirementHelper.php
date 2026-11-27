<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PharIo\Version\UnsupportedVersionConstraintException;
use PharIo\Version\Version;
use PharIo\Version\VersionConstraintParser;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\BetterReflection\Reflection\ReflectionAttribute;
use PHPStan\Php\ConfiguredPhpVersionRangeHelper;
use PHPStan\Php\PhpMinorVersionIterator;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;
use function count;
use function explode;
use function implode;
use function is_numeric;
use function is_string;
use function preg_match;
use function sprintf;
use function strpos;
use function substr_count;
use function version_compare;

final class AttributeVersionRequirementHelper
{

	/**
	 * Matches requirements whose version can be completed to major.minor.patch
	 * without changing their meaning, e.g. `>=8`, `>=8.1` or `^8.1`.
	 * Versions without an operator are deliberately not matched.
	 */
	private const FIXABLE_VERSION_REQUIREMENT = '/^(?P<operator>>=|\^)(?P<space>\s*)(?P<version>\d+(?:\.\d+){0,2})$/';

	private const VERSION_COMPARISON = "/(?P<operator>!=|<|<=|<>|=|==|>|>=)?\s*(?P<version>[\d\.-]+(dev|(RC|alpha|beta)[\d\.])?)[ \t]*\r?$/m";

	private PHPUnitVersion $PHPUnitVersion;

	/**
	 * When phpstan-deprecation-rules is installed, rule reports deprecated usages.
	 */
	private bool $deprecationRulesInstalled;

	/**
	 * Whether warnings about incomplete versions are allowed to be emitted
	 */
	private bool $warnAboutIncompleteVersion;

	private bool $bleedingEdge;

	private ConfiguredPhpVersionRangeHelper $phpVersionRangeHelper;

	public function __construct(
		PHPUnitVersion $PHPUnitVersion,
		ConfiguredPhpVersionRangeHelper $phpVersionRangeHelper,
		bool $deprecationRulesInstalled = false,
		bool $bleedingEdge = false,
		bool $warnAboutIncompleteVersion = true
	)
	{
		$this->PHPUnitVersion = $PHPUnitVersion;
		$this->deprecationRulesInstalled = $deprecationRulesInstalled;
		$this->warnAboutIncompleteVersion = $warnAboutIncompleteVersion;
		$this->bleedingEdge = $bleedingEdge;
		$this->phpVersionRangeHelper = $phpVersionRangeHelper;
	}

	/**
	 * @param array<ReflectionAttribute> $attributes
	 * @param array<Node\AttributeGroup> $attrGroups AST attribute groups of the node $attributes were read from
	 *
	 * @return list<IdentifierRuleError>
	 */
	public function checkVersionRequirement(array $attributes, array $attrGroups, Scope $scope): array
	{
		$errors = [];
		$parser = new VersionConstraintParser();
		$attributeNodes = $this->groupAttributeNodesByName($attrGroups);
		$attributeIndexes = [];
		foreach ($attributes as $attr) {
			$attributeIndex = $attributeIndexes[$attr->getName()] ?? 0;
			$attributeIndexes[$attr->getName()] = $attributeIndex + 1;

			$args = $attr->getArguments();
			if (count($args) !== 1) {
				continue;
			}

			// phpunit does not support named arguments.
			// we handle them here just to make sure we don't crash on them.
			$versionRequirement = $args[0] ?? $args['versionRequirement'] ?? null;
			if (!is_string($versionRequirement)) {
				continue;
			}

			// the following block is mimicing PHPUnit version parsing
			// see https://github.com/sebastianbergmann/phpunit/blob/43c2cd7b96ee1e800b35e4df23b419a88b53111d/src/Metadata/Version/Requirement.php

			if ($this->warnAboutIncompleteVersion($versionRequirement)) {
				$errorBuilder = RuleErrorBuilder::message(
					sprintf('Version requirement %s is incomplete. Expect a version composed of major, minor and patch.', $versionRequirement),
				)
					->identifier('phpunit.attributeRequiresPhpVersion');
				$versionNode = $this->getFixableVersionNode(
					$attributeNodes[$attr->getName()][$attributeIndex] ?? null,
					$versionRequirement,
				);
				$errors[] = $this->buildError($errorBuilder, $versionNode);
			}

			if (
				!is_numeric($versionRequirement)
			) {
				if (!$this->bleedingEdge) {
					continue;
				}

				$pharIoVersions = strpos($attr->getName(), 'RequiresPhpunit') !== false
					? $this->PHPUnitVersion->getPharIoVersions()
					: $this->getAnalyzedPhpVersions();
				if ($pharIoVersions === []) {
					continue;
				}

				try {
					// check composer like version constraints, e.g. ^1  or ~2
					$testPhpVersionConstraint = $parser->parse($versionRequirement);

					foreach ($pharIoVersions as $pharIoVersion) {
						if ($testPhpVersionConstraint->complies($pharIoVersion)) {
							// one of the versions within range matched, check next attribute
							continue 2;
						}
					}
				} catch (UnsupportedVersionConstraintException $e) {
					// test php-src builtin operators as in version_compare()
					if (preg_match(self::VERSION_COMPARISON, $versionRequirement, $matches) <= 0) {
						$errors[] = RuleErrorBuilder::message(
							sprintf($e->getMessage()),
						)
							->identifier('phpunit.attributeRequiresPhpVersion')
							->build();

						continue;
					}

					$operator = $matches['operator'] !== '' ? $matches['operator'] : '>=';

					foreach ($pharIoVersions as $pharIoVersion) {
						if (version_compare($pharIoVersion->getVersionString(), $matches['version'], $operator)) {
							// one of the versions within range matched, check next attribute
							continue 2;
						}
					}
				}

				if (count($pharIoVersions) < 2) {
					throw new ShouldNotHappenException();
				}

				if (strpos($attr->getName(), 'RequiresPhpunit') !== false) {
					$tip = 'PHPUnit version inferred from composer.json requirements.';
				} else {
					$tip = 'PHP version for analysis inferred from NEON config phpVersion or composer.json requirements. Invoke PHPStan with -vvv to get more details.';
				}

				$errors[] = RuleErrorBuilder::message(
					sprintf(
						'Version requirement %s does not match %s...%s.',
						$versionRequirement,
						$pharIoVersions[0]->getVersionString(),
						$pharIoVersions[count($pharIoVersions) - 1]->getVersionString(),
					),
				)
					->tip($tip)
					->identifier('phpunit.attributeRequiresPhpVersion')
					->build();

				continue;
			}

			if ($this->PHPUnitVersion->requiresPhpversionAttributeWithOperator()->yes()) {
				$errors[] = RuleErrorBuilder::message(
					sprintf('Version requirement %s is missing operator.', $versionRequirement),
				)
					->identifier('phpunit.attributeRequiresPhpVersion')
					->build();
			} elseif (
				$this->deprecationRulesInstalled
				&& $this->PHPUnitVersion->deprecatesPhpversionAttributeWithoutOperator()->yes()
			) {
				$errors[] = RuleErrorBuilder::message(
					sprintf('Version requirement %s without operator is deprecated.', $versionRequirement),
				)
					->identifier('phpunit.attributeRequiresPhpVersion')
					->build();
			}
		}
		return $errors;
	}

	/**
	 * @param array<Node\AttributeGroup> $attrGroups
	 *
	 * @return array<string, list<Node\Attribute>>
	 */
	private function groupAttributeNodesByName(array $attrGroups): array
	{
		$attributeNodes = [];
		foreach ($attrGroups as $attrGroup) {
			foreach ($attrGroup->attrs as $attributeNode) {
				$attributeNodes[$attributeNode->name->toString()][] = $attributeNode;
			}
		}

		return $attributeNodes;
	}

	/**
	 * Returns the string literal holding the version requirement, when it is a plain literal in the analysed file.
	 */
	private function getFixableVersionNode(?Node\Attribute $attributeNode, string $versionRequirement): ?String_
	{
		if ($attributeNode === null || count($attributeNode->args) !== 1) {
			return null;
		}

		$versionNode = $attributeNode->args[0]->value;
		if (!$versionNode instanceof String_) {
			return null;
		}

		// make sure the AST node is the one the reflection attribute was read from
		if ($versionNode->value !== $versionRequirement) {
			return null;
		}

		return $versionNode;
	}

	/**
	 * @param RuleErrorBuilder<IdentifierRuleError> $errorBuilder
	 */
	private function buildError(RuleErrorBuilder $errorBuilder, ?String_ $versionNode): IdentifierRuleError
	{
		if ($versionNode === null) {
			return $errorBuilder->build();
		}

		$fixedVersionRequirement = $this->fixVersionRequirement($versionNode->value);
		if ($fixedVersionRequirement === null) {
			return $errorBuilder->build();
		}

		return $errorBuilder->fixNode(
			$versionNode,
			static fn (String_ $versionNode): String_ => new String_($fixedVersionRequirement, ['kind' => $versionNode->getAttribute('kind')]),
		)->build();
	}

	/**
	 * Completes the version to major.minor.patch.
	 * Only applied where the meaning of the requirement stays the same.
	 * Versions without an operator are not fixed.
	 */
	private function fixVersionRequirement(string $versionRequirement): ?string
	{
		if (preg_match(self::FIXABLE_VERSION_REQUIREMENT, $versionRequirement, $matches) !== 1) {
			return null;
		}

		$versionParts = explode('.', $matches['version']);
		while (count($versionParts) < 3) {
			$versionParts[] = '0';
		}

		return $matches['operator'] . $matches['space'] . implode('.', $versionParts);
	}

	/**
	 * @return list<Version>
	 */
	private function getAnalyzedPhpVersions(): array
	{
		// @phpstan-ignore phpstanApi.method
		[$minVersion, $maxVersion] = $this->phpVersionRangeHelper->getVersionRange();
		if ($minVersion !== null && $maxVersion !== null) {
			$versions = [];
			$minorVersionIterator = new PhpMinorVersionIterator(
				$minVersion,
				$maxVersion,
			);
			foreach ($minorVersionIterator as $phpstanVersion) {
				$versions[] = new Version($phpstanVersion->getVersionString());
			}
			return $versions;
		}

		return [];
	}

	// see https://github.com/sebastianbergmann/phpunit/issues/6451
	private function warnAboutIncompleteVersion(string $versionRequirement): bool
	{
		if (!$this->bleedingEdge) {
			return false;
		}

		if (!$this->warnAboutIncompleteVersion) {
			return false;
		}

		if (!$this->PHPUnitVersion->warnsAboutIncompleteVersion()->yes()) {
			return false;
		}

		return substr_count($versionRequirement, '.') !== 2;
	}

}
