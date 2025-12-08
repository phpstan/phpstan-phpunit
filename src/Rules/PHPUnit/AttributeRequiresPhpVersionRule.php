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
use PHPUnit\Framework\TestCase;
use function count;
use function is_numeric;
use function method_exists;
use function preg_match;
use function sprintf;
use function version_compare;

/**
 * @implements Rule<InClassMethodNode>
 */
class AttributeRequiresPhpVersionRule implements Rule
{

	private const VERSION_COMPARISON = "/(?P<operator>!=|<|<=|<>|=|==|>|>=)?\s*(?P<version>[\d\.-]+(dev|(RC|alpha|beta)[\d\.])?)[ \t]*\r?$/m";

	private Version $phpstanPhpVersion;

	private PHPUnitVersion $PHPUnitVersion;

	private TestMethodsHelper $testMethodsHelper;

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

		$this->phpstanPhpVersion = new Version($phpVersion->getVersionString());
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

		$errors = [];
		$parser = new VersionConstraintParser();
		foreach ($reflectionMethod->getAttributesByName('PHPUnit\Framework\Attributes\RequiresPhp') as $attr) {
			$args = $attr->getArguments();
			if (count($args) !== 1) {
				continue;
			}

			if (
				!is_numeric($args[0])
			) {
				try {
					$testPhpVersionConstraint = $parser->parse($args[0]);

					if ($testPhpVersionConstraint->complies($this->phpstanPhpVersion)) {
						continue;
					}
				} catch (UnsupportedVersionConstraintException $e) {
					if (preg_match(self::VERSION_COMPARISON, $args[0], $matches) <= 0) {
						$errors[] = RuleErrorBuilder::message(
							sprintf($e->getMessage()),
						)
							->identifier('phpunit.attributeRequiresPhpVersion')
							->build();

						continue;
					}

					$operator = $matches['operator'] !== '' ? $matches['operator'] : '>=';

					if (version_compare($this->phpstanPhpVersion->getVersionString(), $matches['version'], $operator)) {
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
