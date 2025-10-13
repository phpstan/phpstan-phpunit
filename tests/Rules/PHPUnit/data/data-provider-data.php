<?php

namespace DataProviderDataTest;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{

	#[DataProvider('aProvider')]
	public function testTrim(string $expectedResult, string $input): void
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

