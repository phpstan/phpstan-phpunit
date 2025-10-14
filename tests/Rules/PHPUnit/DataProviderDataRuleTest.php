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
use PHPStan\Rules\PHPUnit\DataProviderHelper;
use PHPStan\Rules\PHPUnit\TestMethodsHelper;
use PHPStan\Rules\Properties\PropertyReflectionFinder;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;

/**
 * @extends RuleTestCase<CompositeRule>
 */
class DataProviderDataRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		$reflectionProvider = $this->createReflectionProvider();

		return new CompositeRule(new DirectRegistry([
			new DataProviderDataRule(
				new TestMethodsHelper($reflectionProvider, self::getContainer()->getByType(FileTypeMapper::class), self::getContainer()->getService('defaultAnalysisParser')),
				new DataProviderHelper($reflectionProvider, self::getContainer()->getByType(FileTypeMapper::class), self::getContainer()->getService('defaultAnalysisParser'), true),
			),
			self::getContainer()->getByType(CallMethodsRule::class)
		]));
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/data-provider-data.php'], [
			[
				'Parameter #2 $input of method DataProviderDataTest\FooTest::testWithAttribute() expects string, int given.',
				23,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\FooTest::testWithAttribute() expects string, false given.',
				27,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\BarTest::testWithAnnotation() expects string, int given.',
				50,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\BarTest::testWithAnnotation() expects string, false given.',
				54,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldingTest::testYield() expects string, int given.',
				79,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldingTest::testYield() expects string, false given.',
				85,
			],
		]);
	}

	/**
	 * @return string[]
	 */
	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/../../../extension.neon',
		];
	}
}
