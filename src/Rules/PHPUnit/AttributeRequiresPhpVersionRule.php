<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PharIo\Version\UnsupportedVersionConstraintException;
use PharIo\Version\Version;
use PharIo\Version\VersionConstraintParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Php\PhpVersion;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\IntegerRangeType;
use PHPUnit\Framework\TestCase;
use function count;
use function is_numeric;
use function preg_match;
use function sprintf;
use function version_compare;

/**
 * @implements Rule<InClassMethodNode>
 */
class AttributeRequiresPhpVersionRule implements Rule
{

	private const VERSION_COMPARISON = "/(?P<operator>!=|<|<=|<>|=|==|>|>=)?\s*(?P<version>[\d\.-]+(dev|(RC|alpha|beta)[\d\.])?)[ \t]*\r?$/m";

	private PHPUnitVersion $PHPUnitVersion;

	private TestMethodsHelper $testMethodsHelper;

	private PhpVersion $fallbackPhpVersion;

	/**
	 * When phpstan-deprecation-rules is installed, it reports deprecated usages.
	 */
	private bool $deprecationRulesInstalled;

	public function __construct(
		PHPUnitVersion $PHPUnitVersion,
		TestMethodsHelper $testMethodsHelper,
		bool $deprecationRulesInstalled,
		PhpVersion $phpVersion
	)
	{
		$this->PHPUnitVersion = $PHPUnitVersion;
		$this->testMethodsHelper = $testMethodsHelper;
		$this->deprecationRulesInstalled = $deprecationRulesInstalled;
		$this->fallbackPhpVersion = $phpVersion;
	}

	public function getNodeType(): string
	{
		return InClassMethodNode::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		$classReflection = $scope->getClassReflection();
		if ($classReflection === null || $classReflection->is(TestCase::class) === false) {
			return [];
		}

		$reflectionMethod = $this->testMethodsHelper->getTestMethodReflection($classReflection, $node->getMethodReflection(), $scope);
		if ($reflectionMethod === null) {
			return [];
		}

		$scopePhpVersion = $scope->getPhpVersion()->getType();
		if ($scopePhpVersion instanceof ConstantIntegerType) {
			$v = new PhpVersion($scopePhpVersion->getValue());
			$phpstanPharIoVersion = new Version($v->getVersionString());
		} elseif ($scopePhpVersion instanceof IntegerRangeType && $scopePhpVersion->getMin() !== null) {
			$v = new PhpVersion($scopePhpVersion->getMin());
			$phpstanPharIoVersion = new Version($v->getVersionString());
		} else {
			$phpstanPharIoVersion = new Version($this->fallbackPhpVersion->getVersionString());
		}

		$errors = [];
		$parser = new VersionConstraintParser();
		foreach ($reflectionMethod->getAttributesByName('PHPUnit\Framework\Attributes\RequiresPhp') as $attr) {
			$args = $attr->getArguments();
			if (count($args) !== 1) {
				continue;
			}

			// the following block is mimicing PHPUnit version parsing
			// see https://github.com/sebastianbergmann/phpunit/blob/43c2cd7b96ee1e800b35e4df23b419a88b53111d/src/Metadata/Version/Requirement.php

			$versionRequirement = $args[0];
			if (
				!is_numeric($versionRequirement)
			) {
				try {
					// check composer like version constraints, e.g. ^1  or ~2
					$testPhpVersionConstraint = $parser->parse($versionRequirement);

					if ($testPhpVersionConstraint->complies($phpstanPharIoVersion)) {
						continue;
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

					if (version_compare($phpstanPharIoVersion->getVersionString(), $matches['version'], $operator)) {
						continue;
					}
				}

				$errors[] = RuleErrorBuilder::message(
					sprintf('Version requirement will always evaluate to false.'),
				)
					->identifier('phpunit.attributeRequiresPhpVersion')
					->build();

				continue;
			}

			if ($this->PHPUnitVersion->requiresPhpversionAttributeWithOperator()->yes()) {
				$errors[] = RuleErrorBuilder::message(
					sprintf('Version requirement is missing operator.'),
				)
					->identifier('phpunit.attributeRequiresPhpVersion')
					->build();
			} elseif (
				$this->deprecationRulesInstalled
				&& $this->PHPUnitVersion->deprecatesPhpversionAttributeWithoutOperator()->yes()
			) {
				$errors[] = RuleErrorBuilder::message(
					sprintf('Version requirement without operator is deprecated.'),
				)
					->identifier('phpunit.attributeRequiresPhpVersion')
					->build();
			}
		}

		return $errors;
	}

}
