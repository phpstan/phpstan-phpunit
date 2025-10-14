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
	public function testWithAnnotation(string $expectedResult, string $input): void
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
