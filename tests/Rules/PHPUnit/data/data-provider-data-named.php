<?php

namespace DataProviderDataTestPhp8;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;


class NamedArgsInProvider extends TestCase
{

	/** @dataProvider aProvider */
	public function testFoo(string $expectedResult, string $input): void
	{
	}

	public function aProvider(): array
	{
		$arr = [
			[
				"input" => 'Hello World',
				"expectedResult" => " Hello World \n"
			]
		];

		if (rand(0,1)) {
			$arr = [
				[
					"input" => 123,
					"expectedResult" => " Hello World \n"
				]
			];
		}
		if (rand(0,1)) {
			$arr = [
				[
					"input" => false,
					"expectedResult" => " Hello World \n"
				]
			];
		}

		return $arr;
	}
}
