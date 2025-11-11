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
		$foo = new \stdClass();

		$this->assertSame(5, $foo->count()); // OK
	}

	public function testAssertSameIsDetectedWithDirectAssertAccess()
	{
		\PHPUnit\Framework\Assert::assertSame(5, count([1, 2, 3]));
	}

	public function testAssertSameWithCountMethodForCountableVariableIsNotOK()
	{
		$foo = new \stdClass();
		$foo->bar = new Bar ();

		$this->assertSame(5, $foo->bar->count());
	}

	public function testRecursiveCount($x)
	{
		$this->assertSame(5, count([1, 2, 3, $x], COUNT_RECURSIVE)); // OK
	}

	public function testNormalCount($x)
	{
		$this->assertSame(5, count([1, 2, 3, $x], COUNT_NORMAL));
	}

	public function testImplicitNormalCount($mode)
	{
		$this->assertSame(5, count([1, 2, 3], $mode));
	}

	public function testUnknownCountable($x, $mode)
	{
		$this->assertSame(5, count($x, $mode)); // OK
	}

	public function testUnknownCountMode($x, $mode)
	{
		$this->assertSame(5, count([1, 2, 3, $x], $mode)); // OK
	}
}

class Bar implements \Countable {
	public function count(): int
	{
		return 1;
	}
}
