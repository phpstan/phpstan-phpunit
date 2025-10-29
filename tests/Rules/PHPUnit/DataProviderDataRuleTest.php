<?php declare(strict_types=1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Testing\CompositeRule;
use PHPStan\Rules\Methods\CallMethodsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<CompositeRule>
 */
class DataProviderDataRuleTest extends RuleTestCase
{
	private ?int $phpunitVersion;

	protected function getRule(): Rule
	{
		$reflectionProvider = $this->createReflectionProvider();
		$phpunitVersion = new PHPUnitVersion($this->phpunitVersion);

		/** @var list<Rule<Node>> $rules */
		$rules = [
			new DataProviderDataRule(
				new TestMethodsHelper(
					self::getContainer()->getByType(FileTypeMapper::class),
					$phpunitVersion
				),
				new DataProviderHelper(
					$reflectionProvider,
					self::getContainer()->getByType(FileTypeMapper::class),
					self::getContainer()->getService('defaultAnalysisParser'),
					$phpunitVersion
				),
				$phpunitVersion,
			),
			self::getContainer()->getByType(CallMethodsRule::class) /** @phpstan-ignore phpstanApi.classConstant */
		];

		return new CompositeRule($rules);
	}

	public function testRule(): void
	{
		$this->phpunitVersion = 10;

		$this->analyse([__DIR__ . '/data/data-provider-data.php'], [
			[
				'Parameter #2 $input of method DataProviderDataTest\FooTest::testWithAttribute() expects string, int given.',
				24,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\FooTest::testWithAttribute() expects string, false given.',
				28,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\BarTest::testWithAnnotation() expects string, int given.',
				51,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\BarTest::testWithAnnotation() expects string, false given.',
				55,
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
				112,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldFromTest::myTestMethod() expects string, false given.',
				116,
			],
			[
				'Method DataProviderDataTest\DifferentArgumentCount::testFoo() invoked with 3 parameters, 2 required.',
				141,
			],
			[
				'Method DataProviderDataTest\DifferentArgumentCount::testFoo() invoked with 1 parameter, 2 required.',
				146,
			],
			[
				'Method DataProviderDataTest\DifferentArgumentCountWithReusedDataprovider::testFoo() invoked with 3 parameters, 2 required.',
				177,
			],
			[
				'Method DataProviderDataTest\DifferentArgumentCountWithReusedDataprovider::testFoo() invoked with 1 parameter, 2 required.',
				182,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\UnionTypeReturnTest::testFoo() expects string, int given.',
				216,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldFromExpr::testFoo() expects string, int given.',
				236,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\YieldFromExpr::testFoo() expects string, true given.',
				238,
			],
			[
				'Parameter #1 $si of method DataProviderDataTest\TestInvalidVariadic::testBar() expects int, string given.',
				295,
			],
			[
				'Parameter #1 $s of method DataProviderDataTest\TestInvalidVariadic::testFoo() expects string, int given.',
				296,
			],
			[
				'Parameter #1 $si of method DataProviderDataTest\TestInvalidVariadic2::testBar() expects int, string given.',
				317,
			],
			[
				'Parameter #2 ...$moreS of method DataProviderDataTest\TestInvalidVariadic2::testFoo() expects int, string given.',
				317,
			],
			[
				'Parameter #4 ...$moreS of method DataProviderDataTest\TestInvalidVariadic2::testFoo() expects int, string given.',
				317,
			],
			[
				'Parameter #1 $s of method DataProviderDataTest\TestInvalidVariadic2::testFoo() expects string, int given.',
				318,
			],
			[
				'Parameter #1 $i of method DataProviderDataTest\TestArrayIterator::testBar() expects int, int|string given.',
				362,
			],
			[
				'Parameter #1 $i of method DataProviderDataTest\TestArrayIterator::testFoo() expects int, int|string given.',
				362,
			],
			[
				'Parameter #1 $s1 of method DataProviderDataTest\TestArrayIterator::testFooBar() expects string, int|string given.',
				362,
			],
			[
				'Parameter #1 $si of method DataProviderDataTest\TestWrongTypedIterable::testBar() expects int, string given.',
				380,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\AbstractBaseTest::testWithAttribute() expects string, int given.',
				407,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\AbstractBaseTest::testWithAttribute() expects string, false given.',
				411,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\ConstantArrayUnionTypeReturnTest::testFoo() expects string, int given.',
				446,
			],
			[
				'Method DataProviderDataTest\ConstantArrayDifferentLengthUnionTypeReturnTest::testFoo() invoked with 3 parameters, 2 required.',
				484,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\ConstantArrayDifferentLengthUnionTypeReturnTest::testFoo() expects string, int given.',
				484,
			],
			[
				'Parameter #2 $input of method DataProviderDataTest\ConstantArrayUnionWithDifferentValueTypeReturnTest::testFoo() expects string, int|string given.',
				517,
			],
		]);
	}

	public function testRulePhp8(): void
	{
		if (PHP_VERSION_ID < 80000) {
			self::markTestSkipped('PHPUnit11 requires PHP 8.0.');
		}

		$this->phpunitVersion = 10;

		$this->analyse([__DIR__ . '/data/data-provider-data-named.php'], [
			[
				'Parameter #1 $expectedResult of method DataProviderDataTestPhp8\NamedArgsInProvider::testFoo() expects string, int given.',
				44
			],
			[
				'Parameter #1 $expectedResult of method DataProviderDataTestPhp8\NamedArgsInProvider::testFoo() expects string, false given.',
				44
			],
			[
				'Parameter #1 $si of method DataProviderDataTestPhp8\TestWrongOffsetNameArrayShapeIterable::testBar() expects int, string given.',
				58
			],
			[
				'Parameter #1 $si of method DataProviderDataTestPhp8\TestWrongTypeInArrayShapeIterable::testBar() expects int, string given.',
				79
			],
		]);
	}


	public function testVariadicMethod(): void
	{
		$this->phpunitVersion = 10;

		$this->analyse([__DIR__ . '/data/data-provider-variadic-method.php'], [
			[
				'Method DataProviderVariadicMethod\FooTest::testProvide2() invoked with 1 parameter, at least 2 required.',
				12,
			],
			[
				'Parameter #1 $a of method DataProviderVariadicMethod\FooTest::testProvide() expects int, string given.',
				13,
			],
			[
				'Method DataProviderVariadicMethod\FooTest::testProvide2() invoked with 1 parameter, at least 2 required.',
				13,
			],
			[
				'Parameter #1 $a of method DataProviderVariadicMethod\FooTest::testProvide2() expects int, string given.',
				13,
			],
			[
				'Parameter #2 ...$rest of method DataProviderVariadicMethod\FooTest::testProvide() expects string, int given.',
				15,
			],
			[
				'Parameter #3 ...$rest of method DataProviderVariadicMethod\FooTest::testProvide() expects string, int given.',
				15,
			],
			[
				'Parameter #2 $two of method DataProviderVariadicMethod\FooTest::testProvide2() expects string, int given.',
				15,
			],
			[
				'Parameter #3 ...$rest of method DataProviderVariadicMethod\FooTest::testProvide2() expects string, int given.',
				15,
			],
		]);
	}

	public function testTrimmingArgs(): void
	{
		$this->phpunitVersion = 10;

		$this->analyse([__DIR__ . '/data/data-provider-trimming-args.php'], [
			[
				'Method DataProviderTrimmingArgs\FooTest::testProvide() invoked with 2 parameters, 1 required.',
				12,
			],
			[
				'Method DataProviderTrimmingArgs\FooTest::testProvide2() invoked with 2 parameters, 1 required.',
				12,
			],
			[
				'Method DataProviderTrimmingArgs\FooTest::testProvide() invoked with 2 parameters, 1 required.',
				13,
			],
			[
				'Method DataProviderTrimmingArgs\FooTest::testProvide2() invoked with 2 parameters, 1 required.',
				13,
			],
			[
				'Parameter #6 ...$m of method DataProviderTrimmingArgs\BazTest::testProvide() expects int, string given.',
				90,
			],
		]);
	}

	static public function provideNamedArgumentVersions(): iterable
	{
		return [
			[null],
			[10],
			[11],
		];
	}

	/**
	 * @dataProvider provideNamedArgumentVersions
	 */
	#[DataProvider('provideNamedArgumentVersions')]
	public function testNamedArgumentsInDataProviders(?int $phpunitVersion): void
	{
		$this->phpunitVersion = $phpunitVersion;

		if ($phpunitVersion >= 11) {
			$errors = [];
			$this->analyse([__DIR__ . '/data/data-provider-named-args.php'], [
			]);
		} else {
			$errors = [
				[
					'Parameter #1 $int of method DataProviderNamedArgs\FooTest::testFoo() expects int, string given.',
					26
				],
				[
					'Parameter #2 $string of method DataProviderNamedArgs\FooTest::testFoo() expects string, int given.',
					26
				],
			];
		}

		$this->analyse([__DIR__ . '/data/data-provider-named-args.php'], $errors);
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
