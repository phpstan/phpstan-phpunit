<?php declare(strict_types = 1);

namespace ExampleTestCase;

class AssertSameWithCountTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertSameWithCount()
	{
		$this->assertSame(5, count([1, 2, 3]));
	}

	public function testAssertSameWithCountMethodIsOK()
	{
		$this->assertSame(5, $this->count()); // OK
	}

}
