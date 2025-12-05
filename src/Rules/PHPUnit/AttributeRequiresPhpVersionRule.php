<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use Composer\Semver\Constraint\ConstraintInterface;
use Composer\Semver\VersionParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Php\PhpVersion;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;
use function count;
use function is_numeric;
use function sprintf;

/**
 * @implements Rule<InClassMethodNode>
 */
class AttributeRequiresPhpVersionRule implements Rule
{

	private ConstraintInterface $phpstanVersionConstraint;

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

		$parser = new VersionParser();
		$this->phpstanVersionConstraint = $parser->parseConstraints($phpVersion->getVersionString());
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
		$parser = new VersionParser();
		foreach ($reflectionMethod->getAttributesByName('PHPUnit\Framework\Attributes\RequiresPhp') as $attr) {
			$args = $attr->getArguments();
			if (count($args) !== 1) {
				continue;
			}

			if (
				is_numeric($args[0])
			) {
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

				continue;
			}

			try {
				$testPhpVersionConstraint = $parser->parseConstraints($args[0]);
			} catch (UnexpectedValueException $e) {
				$errors[] = RuleErrorBuilder::message(
					sprintf($e->getMessage()),
				)
					->identifier('phpunit.attributeRequiresPhpVersion')
					->build();

				continue;
			}

			if ($this->phpstanVersionConstraint->matches($testPhpVersionConstraint)) {
				continue;
			}

			$errors[] = RuleErrorBuilder::message(
				sprintf('Version requirement will always evaluate to false.'),
			)
				->identifier('phpunit.attributeRequiresPhpVersion')
				->build();
		}

		return $errors;
	}

}
