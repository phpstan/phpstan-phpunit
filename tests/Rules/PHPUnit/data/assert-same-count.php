<?php declare(strict_types = 1);

namespace ExampleTestCase;

class AssertSameWithCountTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertSameWithCount()
	{
		$this->assertSame(5, count([1, 2, 3]));
	}

	public function testAssertSameWithCountExpectedWithCount()
	{
		$this->assertSame(count([10, 20]), count([1, 2, 3]));
	}

	public function testAssertSameWithCountExpectedMethodWithCountMethod()
	{
		$foo = new \stdClass();
		$foo->bar = new Bar ();

		$this->assertSame($foo->bar->count(), count([1, 2, 3]));
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

	public function testAssertSameWithCountExpectedWithCountMethodForCountableVariableIsNot()
	{
		$foo = new \stdClass();
		$foo->bar = new Bar ();

		$this->assertSame(count([10, 20]), $foo->bar->count());
	}

	public function testAssertSameWithCountExpectedMethodWithCountMethodForCountableVariableIsNot()
	{
		$foo = new \stdClass();
		$foo->bar = new Bar ();
		$foo2 = new \stdClass();
		$foo2->bar = new Bar ();

		$this->assertSame($foo2->bar->count(), $foo->bar->count());
	}

}

class Bar implements \Countable {
	public function count(): int
	{
		return 1;
	}
};
