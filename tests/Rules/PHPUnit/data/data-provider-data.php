<?php

namespace DataProviderDataTest;

use PHPUnit\Framework\Attributes\DataProvider;
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

