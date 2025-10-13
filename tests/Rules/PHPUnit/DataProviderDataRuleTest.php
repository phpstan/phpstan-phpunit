<?php declare(strict_types=1);

namespace Rules\PHPUnit;

use PHPStan\Rules\CompositeRule;
use PHPStan\Rules\DirectRegistry;
use PHPStan\Rules\FunctionCallParametersCheck;
use PHPStan\Rules\Methods\CallMethodsRule;
use PHPStan\Rules\Methods\MethodCallCheck;
use PHPStan\Rules\NullsafeCheck;
use PHPStan\Rules\PhpDoc\UnresolvableTypeHelper;
use PHPStan\Rules\PHPUnit\DataProviderDataRule;
use PHPStan\Rules\Properties\PropertyReflectionFinder;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<DataProviderDataRule>
 */
class DataProviderDataRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		$reflectionProvider = $this->createReflectionProvider();

		$ruleLevelHelper = new RuleLevelHelper($reflectionProvider, true, false, true, true, false, false, true);


		return new CompositeRule(new DirectRegistry([
			new DataProviderDataRule(
				$reflectionProvider
			),
			new CallMethodsRule(
				new MethodCallCheck($reflectionProvider, $ruleLevelHelper, true, true),
				new FunctionCallParametersCheck($ruleLevelHelper, new NullsafeCheck(), new UnresolvableTypeHelper(), new PropertyReflectionFinder(), true, true, true, true),
			)
		]));
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/data-provider-data.php'], [
			[
				'Parameter #2 $input of method DataProviderDataTest\FooTest::testTrim() expects string, int given.',
				23,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\FooTest::testTrim() expects string, false given.',
				27,
			],
		]);
	}

	/**
	 * @return string[]
	 */
	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/data-provider-data.neon',
		];
	}
}
