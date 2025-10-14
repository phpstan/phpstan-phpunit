<?php declare(strict_types=1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Testing\CompositeRule;
use PHPStan\Rules\Methods\CallMethodsRule;
use PHPStan\Rules\PHPUnit\DataProviderDataRule;
use PHPStan\Rules\PHPUnit\DataProviderHelper;
use PHPStan\Rules\PHPUnit\TestMethodsHelper;
use PHPStan\Rules\Rule;
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

		return new CompositeRule([
			new DataProviderDataRule(
				new TestMethodsHelper(
					self::getContainer()->getByType(FileTypeMapper::class),
					true
				),
				new DataProviderHelper(
					$reflectionProvider,
					self::getContainer()->getByType(FileTypeMapper::class),
					self::getContainer()->getService('defaultAnalysisParser'),
					true
				),

			),
			self::getContainer()->getByType(CallMethodsRule::class) /** @phpstan-ignore phpstanApi.classConstant */
		]);
	}

	public function testRule(): void
	{
		if (PHP_VERSION_ID < 80000) {
			self::markTestSkipped();
		}

		$this->analyse([__DIR__ . '/data/data-provider-data.php'], [
			[
				'Parameter #2 $input of method DataProviderDataTest\FooTest::testWithAttribute() expects string, int given.',
				19,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\FooTest::testWithAttribute() expects string, false given.',
				19,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\BarTest::testWithAnnotation() expects string, int given.',
				46,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\BarTest::testWithAnnotation() expects string, false given.',
				46,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldTest::myTestMethod() expects string, int given.',
				80,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldTest::myTestMethod() expects string, false given.',
				86,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldFromTest::myTestMethod() expects string, int given.',
				107,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldFromTest::myTestMethod() expects string, false given.',
				107,
			],
			[
				'Method DataProviderDataTest\DifferentArgumentCount::testFoo() invoked with 3 parameters, 2 required.',
				136,
			],
			[
				'Method DataProviderDataTest\DifferentArgumentCount::testFoo() invoked with 1 parameter, 2 required.',
				136,
			],
			[
				'Method DataProviderDataTest\DifferentArgumentCountWithReusedDataprovider::testFoo() invoked with 3 parameters, 2 required.',
				172,
			],
			[
				'Method DataProviderDataTest\DifferentArgumentCountWithReusedDataprovider::testFoo() invoked with 1 parameter, 2 required.',
				172,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\UnionTypeReturnTest::testFoo() expects string, int given.',
				216,
			],
			[
				'Parameter $input of method DataProviderDataTest\NamedArgsInProvider::testFoo() expects string, int given.',
				255,
			],
			[
				'Parameter $input of method DataProviderDataTest\NamedArgsInProvider::testFoo() expects string, false given.',
				255,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldFromExpr::testFoo() expects string, int given.',
				275,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldFromExpr::testFoo() expects string, true given.',
				277,
			],
			[
				'Parameter #1 $si of method DataProviderDataTest\TestInvalidVariadic::testBar() expects int, string given.',
				333,
			],
			[
				'Parameter #1 $s of method DataProviderDataTest\TestInvalidVariadic::testFoo() expects string, int given.',
				333,
			],
			[
				'Parameter #1 $si of method DataProviderDataTest\TestInvalidVariadic2::testBar() expects int, string given.',
				355,
			],
			[
				'Parameter #2 ...$moreS of method DataProviderDataTest\TestInvalidVariadic2::testFoo() expects int, string given.',
				355,
			],
			[
				'Parameter #4 ...$moreS of method DataProviderDataTest\TestInvalidVariadic2::testFoo() expects int, string given.',
				355,
			],
			[
				'Parameter #1 $s of method DataProviderDataTest\TestInvalidVariadic2::testFoo() expects string, int given.',
				355,
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
