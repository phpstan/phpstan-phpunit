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
