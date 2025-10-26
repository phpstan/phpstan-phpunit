<?php

namespace DataProviderVariadicMethod;

use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{

	public function dataProvide(): iterable
	{
		yield [1];
		yield ['foo'];
		yield [1, 'foo', 'bar'];
		yield [1, 2, 3];
	}

	/**
	 * @dataProvider dataProvide
	 */
	public function testProvide(int $a, string ...$rest): void
	{

	}

	/**
	 * @dataProvider dataProvide
	 */
	public function testProvide2(int $a, string $two, string ...$rest): void
	{

	}

}

class BarTest extends TestCase
{

	public function dataProvide(): iterable
	{
		yield [1, 2];
		yield [3, 4];
	}

	/**
	 * @dataProvider dataProvide
	 */
	public function testProvide(int $i): void
	{

	}

	/**
	 * @dataProvider dataProvide
	 */
	public function testProvide2(int ...$args): void
	{

	}

}
