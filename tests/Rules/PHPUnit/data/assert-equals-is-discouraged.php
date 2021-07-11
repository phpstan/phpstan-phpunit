<?php declare(strict_types = 1);

namespace ExampleTestCase;

class AssertEqualsIsDiscouragedTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertEqualsIsDiscouraged()
	{
		// assertSame can be used as both are of same type
		$this->assertEquals('a', 'b');
		$this->assertEquals(1, 2);
		$this->assertEquals(true, false);

		// comparing floats without delta
		$this->assertEquals(1.0, 2.0);

		// comparing floats with delta
		$this->assertEquals(1.0, 2.0, '', 0.01);

		$this->assertEquals(1, '1'); // assertEquals without comment on previous line

		// with incorrect comment
		$this->assertEquals(1, '1');

		// assertEquals because I want it! But sadly, the comment is not just above the assert.

		$this->assertEquals(1, '1');

		// assertEquals because I want it!
		$this->assertEquals(1, '1');
	}

}
