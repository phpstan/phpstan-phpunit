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


class TestWrongOffsetNameArrayShapeIterable extends TestCase
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
	 * @return iterable<array{wrong: string}>
	 */
	public function data(): iterable
	{
	}
}


class TestWrongTypeInArrayShapeIterable extends TestCase
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
	 * @return iterable<array{si: string}>
	 */
	public function data(): iterable
	{
	}
}


class TestValidArrayShapeIterable extends TestCase
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
	 * @return iterable<array{si: int}>
	 */
	public function data(): iterable
	{
	}
}
