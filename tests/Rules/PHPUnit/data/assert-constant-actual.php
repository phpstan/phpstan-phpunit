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
	}

}
