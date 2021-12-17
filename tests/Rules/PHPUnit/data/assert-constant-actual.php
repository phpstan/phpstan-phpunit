<?php declare(strict_types = 1);

namespace ExampleTestCase;

class AssertWithConstantActualTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertionWithConstantActualIsDetected()
	{
		$this->assertSame('a', 'a');

		$expected = 'a';
		$actual = rand(1, 2);

		// Incorrect order for `$expected` and `$actual`.
		$this->assertSame($actual, $expected);

		// Correct order for `$expected` and `$actual`.
		$this->assertSame($expected, $actual);

		// Correct use of a string result.
		$this->assertSame('foo', constantActualTestFunction('foo'));
	}

}

function constantActualTestFunction(string $type) : string {
	switch ( $type ) {
		case 'foo':
			return 'foo';
		case 'bar':
			return 'bar';
	}

	return 'baz';
}
