<?php declare(strict_types = 1);

namespace ExampleTestCaseFix;

use const COUNT_RECURSIVE;

class AssertSameWithCountTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertSameWithCount()
	{
		$this->assertSame(5, count([1, 2, 3]));
	}

	public function testAssertSameWithCountCallsInOtherArguments($expected, $actual, \Countable $countable)
	{
		$this->assertSame(count($expected), count($actual));
		$this->assertSame(count($expected), count($actual), getMessage());
		$this->assertSame(count($expected), $countable->count(), getMessage());
	}

	public function testAssertSameWithCountRecursive($x)
	{
		$this->assertSame(5, count([1, 2, 3, $x], COUNT_RECURSIVE));
	}

	public function testAssertSameWithCountMethodForCountableVariableIsNotOK()
	{
		$bar = new \ExampleTestCaseFix\Bar ();

		$this->assertSame(5, $bar->count());
	}

	public function testAssertSameWithCountMethodForCountablePropertyFetchIsNotOK()
	{
		$foo = new \stdClass();
		$foo->bar = new Bar ();

		$this->assertSame(5, $foo->bar->count());
	}

}

class Bar implements \Countable {
	public function count(): int
	{
		return 1;
	}
}
