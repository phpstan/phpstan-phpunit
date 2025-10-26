<?php

namespace DataProviderTrimmingArgs;

use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
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
	public function testProvide2(int $i): void
	{

	}

}

class BarTest extends TestCase
{

	/**
	 * @return array<array<string>>
	 */
	public function getData(): array
	{
		return [];
	}

	public function dataProvide(): array
	{
		return $this->getData();
	}

	/**
	 * @dataProvider dataProvide
	 */
	public function testProvide(string ...$arg): void
	{

	}

	/**
	 * @dataProvider dataProvide
	 */
	public function testProvide2(string $arg): void
	{

	}

}
