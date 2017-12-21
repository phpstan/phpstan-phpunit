<?php declare(strict_types = 1);

namespace ExampleTestCase;

class AssertSameBooleanExpectedTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertSameWithBooleanAsExpected()
	{
		$this->assertSame(true, 'a');
		$this->assertSame(false, 'a');

		$truish = true;
		$this->assertSame($truish, true);

		$falsish = false;
		$this->assertSame($falsish, false);

		/** @var bool $a */
		$a = null;
		$this->assertSame($a, 'b'); // OK
	}

}
