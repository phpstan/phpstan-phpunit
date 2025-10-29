<?php

namespace DataProviderNamedArgs;

class FooTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @dataProvider dataProvider
	 */
	public function testFoo(
		int    $int,
		string $string
	): void
	{
		$this->assertTrue(true);
	}

	public static function dataProvider(): iterable
	{
		yield 'even' => [
			'int' => 50,
			'string' => 'abc',
		];

		yield 'odd' => [
			'string' => 'def',
			'int' => 51,
		];
	}
}

