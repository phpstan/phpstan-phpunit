<?php declare(strict_types = 1);

namespace ExampleTestCase;

class AssertSameNullExpectedTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertSameWithNullAsExpected()
	{
		$this->assertSame(null, 'a');

		$a = null;
		$this->assertSame($a, 'b');

		$this->assertSame('a', 'b'); // OK

		/** @var string|null $c */
		$c = null;
		$this->assertSame($c, 'foo'); // nullable is OK
	}

	public function testAssertSameIsDetectedWithDirectAssertAccess()
	{
		\PHPUnit\Framework\Assert::assertSame(null, 'foo');
	}

}
