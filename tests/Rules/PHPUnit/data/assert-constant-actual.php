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

	public function testAssertionWithConstantBooleanActualIsDetected()
	{
		// Incorrect use of a constant boolean for `$actual`.
		$this->assertSame(true, false);

		$expected = false;
		$actual = rand(1, 2);

		// Incorrect order for `$expected` and `$actual`.
		$this->assertSame($actual, $expected);

		// Correct order for `$expected` and `$actual`.
		$this->assertSame($expected, $actual);

		// Correct use of an boolean result.
		$this->assertSame('foo', returnBooleanFunction());
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

	public function testCheckAllAssertionMethodsWorkAsExpected()
	{
		$actualInt = rand(1, 2);
		$actualString = (string) rand(1, 2);

		// Correct usage.
		$this->assertEquals('a', $actualString);
		$this->assertFileEquals('a', $actualString);
		$this->assertFileNotEquals('a', $actualString);
		$this->assertGreaterThan('a', $actualInt);
		$this->assertInstanceOf('a', $actualInt);
		$this->assertLessThan('a', $actualInt);
		$this->assertNotEquals('a', $actualString);
		$this->assertNotInstanceOf('a', $actualInt);
		$this->assertNotSame('a', $actualString);
		$this->assertSame('a', $actualString);
		$this->assertStringEqualsFile('a', $actualString);
		$this->assertStringNotEqualsFile('a', $actualString);

		// Incorrect usage.
		$this->assertEquals($actualString, 'a');
		$this->assertFileEquals($actualString, 'a');
		$this->assertFileNotEquals($actualString, 'a');
		$this->assertGreaterThan($actualInt, 'a');
		$this->assertInstanceOf($actualString, 'a');
		$this->assertLessThan($actualInt, 'a');
		$this->assertNotEquals($actualString, 'a');
		$this->assertNotInstanceOf($actualString, 'a');
		$this->assertNotSame($actualString, 'a');
		$this->assertSame($actualString, 'a');
		$this->assertStringEqualsFile($actualString, 'a');
		$this->assertStringNotEqualsFile($actualString, 'a');
	}

}

function returnStringFunction() : string {
	return 'baz';
}

function returnIntegerFunction() : int {
	return 789;
}

function returnBooleanFunction() : bool {
	return false;
}

function returnArrayFunction() : array {
	return ['baz'];
}
