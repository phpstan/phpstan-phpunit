<?php declare(strict_types = 1);

namespace MockMethodCall;

class Foo extends \PHPUnit\Framework\TestCase
{

	public function testGoodMethod()
	{
		$this->createMock(Bar::class)->method('doThing');
	}

	public function testBadMethod()
	{
		$this->createMock(Bar::class)->method('doBadThing');
	}

	public function testBadMethodWithExpectation()
	{
		$this->createMock(Bar::class)->expects($this->once())->method('doBadThing');
	}

	public function testWithAnotherObject()
	{
		$bar = new BarWithMethod();
		$bar->method('doBadThing');
	}

	public function testWithMockBuilder()
	{
		$this->getMockBuilder(Bar::class)->getMock()->method('doBadThing');
	}

	public function testWhenAddingMethod()
	{
		$this->getMockBuilder(Bar::class)->setMethods(['doBadThing'])->getMock()->method('doBadThing');
	}

}

class Bar {
	public function doThing()
	{
		return 1;
	}
};

class BarWithMethod {
	public function method(string $string)
	{
		return $string;
	}
};
