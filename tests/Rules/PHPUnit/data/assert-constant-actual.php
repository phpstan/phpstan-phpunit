<?php declare(strict_types = 1);

namespace ExampleTestCase;

class AssertWithConstantActualTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertionWithConstantStringActualIsDetected()
	{
		// Incorrect use of a constant string for `$actual`.
		$this->assertSame('a', 'a');

		$expected = 'a';
		$actual = rand(1, 2);

		// Incorrect order for `$expected` and `$actual`.
		$this->assertSame($actual, $expected);

		// Correct order for `$expected` and `$actual`.
		$this->assertSame($expected, $actual);

		// Correct use of a string result.
		$this->assertSame('foo', returnStringFunction());
	}

	public function testAssertionWithConstantIntegerActualIsDetected()
	{
		// Incorrect use of a constant integer for `$actual`.
		$this->assertSame(123, 123);

		$expected = 123;
		$actual = rand(1, 2);

		// Incorrect order for `$expected` and `$actual`.
		$this->assertSame($actual, $expected);

		// Correct order for `$expected` and `$actual`.
		$this->assertSame($expected, $actual);

		// Correct use of an integer result.
		$this->assertSame('foo', returnIntegerFunction());
	}

	public function testAssertionWithConstantArrayActualIsDetected()
	{
		// Incorrect use of a constant array for `$actual`.
		$this->assertSame(['a'], ['a']);

		$expected = ['a'];
		$actual = [rand(1, 2)];

		// Incorrect order for `$expected` and `$actual`.
		$this->assertSame($actual, $expected);

		// Correct order for `$expected` and `$actual`.
		$this->assertSame($expected, $actual);

		// Correct use of an array result.
		$this->assertSame(['foo'], returnArrayFunction());
	}

}

function returnStringFunction() : string {
	return 'baz';
}

function returnIntegerFunction() : int {
	return 789;
}

function returnArrayFunction() : array {
	return ['baz'];
}
