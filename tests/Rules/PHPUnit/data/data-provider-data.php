<?php

namespace DataProviderDataTest;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{

	#[DataProvider('aProvider')]
	public function testWithAttribute(string $expectedResult, string $input): void
	{
	}

	static public function aProvider(): array
	{
		return [
			[
				'Hello World',
				" Hello World \n",
			],
			[
				'Hello World',
				123,
			],
			[
				'Hello World',
				false,
			],
		];
	}
}

class BarTest extends TestCase
{

	/** @dataProvider aProvider */
	public function testWithAnnotation(string $expectedResult, string $input): void
	{
	}

	public function aProvider(): array
	{
		return [
			[
				'Hello World',
				" Hello World \n",
			],
			[
				'Hello World',
				123,
			],
			[
				'Hello World',
				false,
			],
		];
	}
}

class YieldTest extends TestCase
{

	#[DataProvider('yieldProvider')]
	#[Test]
	public function myTestMethod(string $expectedResult, string $input): void
	{
	}

	public function yieldProvider(): iterable
	{
		yield
			[
				'Hello World',
				" Hello World \n",
			];

		yield
			[
				'Hello World',
				123,
			];

		yield
			[
				'Hello World',
				false,
			];
	}
}

class YieldFromTest extends TestCase
{

	/**
	 * @dataProvider yieldProvider
	 * @test
	 */
	public function myTestMethod(string $expectedResult, string $input): void
	{
	}

	public function yieldProvider(): iterable
	{
		yield from [
			[
				'Hello World',
				" Hello World \n",
			],
			[
				'Hello World',
				123,
			],
			[
				'Hello World',
				false,
			]
		];
	}
}

class DifferentArgumentCount extends TestCase
{

	/**
	 * @dataProvider yieldProvider
	 */
	public function testFoo(string $expectedResult, string $input): void
	{
	}

	public function yieldProvider(): iterable
	{
		yield from [
			[
				'Hello World',
				" Hello World \n",
			],
			[
				'Hello World',
				'abc',
				123,
			],
			[
				'Hello World',
			]
		];
	}
}

class DifferentArgumentCountWithReusedDataprovider extends TestCase
{

	/**
	 * @dataProvider yieldProvider
	 */
	public function testFoo(string $expectedResult, string $input): void
	{
	}

	/**
	 * @dataProvider yieldProvider
	 */
	public function testBar(string $expectedResult): void
	{
	}

	public function yieldProvider(): iterable
	{
		yield from [
			[
				'Hello World',
				" Hello World \n",
			],
			[
				'Hello World',
				'abc',
				123,
			],
			[
				'Hello World',
			]
		];
	}
}


class UnionTypeReturnTest extends TestCase
{

	/** @dataProvider aProvider */
	public function testFoo(string $expectedResult, string $input): void
	{
	}

	public function aProvider(): array
	{
		$arr = [
			[
				'Hello World',
				" Hello World \n"
			]
		];

		if (rand(0,1)) {
			$arr = [
				[
					'Hello World',
					123
				]
			];
		}

		return $arr;
	}
}


class YieldFromExpr extends TestCase
{

	/** @dataProvider aProvider */
	public function testFoo(string $expectedResult, string $input): void
	{
	}

	public function aProvider(): iterable
	{
		yield [
			'Hello World',
			" Hello World \n",
		];

		yield from $this->moreData();

		yield [
			'Hello World',
			true,
		];
	}

	/**
	 * @return array{array{'Hello World', 123}}
	 */
	private function moreData(): array
	{
		return [
			[
				'Hello World',
				123,
			]
		];
	}
}

class TestValidVariadic extends TestCase
{
	/** @dataProvider aProvider */
	public function testBar(string $s): void
	{
	}

	/** @dataProvider aProvider */
	public function testFoo(string $s, string ...$moreS): void
	{
	}

	public function aProvider(): iterable
	{
		return [
			["hello", "world", "foo", "bar"],
			["hi", "ho"],
			["nope"]
		];
	}
}

class TestInvalidVariadic extends TestCase
{
	/** @dataProvider aProvider */
	public function testBar(int $si): void
	{
	}

	/** @dataProvider aProvider */
	public function testFoo(string $s, string ...$moreS): void
	{
	}

	public function aProvider(): iterable
	{
		return [
			["hello", "world", "foo", "bar"],
			[123]
		];
	}
}


class TestInvalidVariadic2 extends TestCase
{
	/** @dataProvider aProvider */
	public function testBar(int $si): void
	{
	}

	/** @dataProvider aProvider */
	public function testFoo(string $s, int ...$moreS): void
	{
	}

	public function aProvider(): iterable
	{
		return [
			["hello", "world", 5, "bar"],
			[123]
		];
	}
}

class TestTypedIterable extends TestCase
{
	/** @dataProvider aProvider */
	public function testBar(int $si): void
	{
	}

	public function aProvider(): iterable
	{
		return $this->data();
	}

	/**
	 * @return iterable<array<int>>
	 */
	public function data(): iterable
	{
	}
}

class TestArrayIterator extends TestCase
{
	/** @dataProvider aProvider */
	public function testBar(int $i): void
	{
	}

	/** @dataProvider aProvider */
	public function testFoo(int $i, string $si): void
	{
	}

	/** @dataProvider aProvider */
	public function testFooBar(string $s1, string $s2): void
	{
	}

	public function aProvider(): iterable
	{
		return new \ArrayIterator([
			[1],
			[2, "hello"],
			["no"],
			["no", "yes"],
		]);
	}
}

class TestWrongTypedIterable extends TestCase
{
	/** @dataProvider aProvider */
	public function testBar(int $si): void
	{
	}

	public function aProvider(): iterable
	{
		return $this->data();
	}

	/**
	 * @return iterable<array<string>>
	 */
	public function data(): iterable
	{
	}
}


abstract class AbstractBaseTest extends TestCase
{

	#[DataProvider('aProvider')]
	public function testWithAttribute(string $expectedResult, string $input): void
	{
	}

	static public function aProvider(): array
	{
		return [
			[
				'Hello World',
				" Hello World \n",
			],
			[
				'Hello World',
				123,
			],
			[
				'Hello World',
				false,
			],
		];
	}
}


class ConstantArrayUnionTypeReturnTest extends TestCase
{

	/** @dataProvider aProvider */
	public function testFoo(string $expectedResult, string $input): void
	{
	}

	public function aProvider(): array
	{
		if (rand(0,1)) {
			$arr = [
				[
					'Hello World',
					123
				]
			];
		} else {
			$arr = [
				[
					'Hello World',
					" Hello World \n"
				]
			];
		}

		return $arr;
	}
}

class ConstantArrayDifferentLengthUnionTypeReturnTest extends TestCase
{

	/** @dataProvider aProvider */
	public function testFoo(string $expectedResult, string $input): void
	{
	}

	public function aProvider(): array
	{
		if (rand(0,1)) {
			$arr = [
				[
					'Hello World',
					123
				]
			];
		} elseif (rand(0,1)) {
			$arr = [
				[
					'Hello World',
					'Hello World',
				]
			];
		} else {
			$arr = [
				[
					'Hello World',
					" Hello World \n",
					" Too much \n",
				]
			];
		}

		return $arr;
	}
}

class ConstantArrayUnionWithDifferentValueTypeReturnTest extends TestCase
{

	/** @dataProvider aProvider */
	public function testFoo(string $expectedResult, string $input): void
	{
	}

	public function aProvider(): array
	{
		if (rand(0,1)) {
			$arr = [
				[
					'Hellooo',
					' World',
				]
			];
		} else {
			$a = rand(0,1) ? 'Hello' : 'World';
			$b = rand(0,1) ? " Hello World \n" : 123;

			$arr = [
				[
					$a,
					$b
				]
			];
		}

		return $arr;
	}
}
