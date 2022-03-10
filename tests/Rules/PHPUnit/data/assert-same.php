<?php declare(strict_types = 1);

namespace ExampleTestCase;

class FooTestCase extends \PHPUnit\Framework\TestCase
{

	public function testObviouslyNotSameAssertSame()
	{
		$this->assertSame('1', 1);
		$this->assertSame('1', new \stdClass());
		$this->assertSame(1, $this->returnsString());
		$this->assertSame('1', self::returnsInt());
		$this->assertSame(['a', 'b'], [1, 2]);

		self::assertSame('1', 2); // test self
		static::assertSame('1', 2); // test static
		parent::assertSame('1', 2); // test parent
	}

	private function returnsString(): string
	{
		return 'foo';
	}

	private static function returnsInt(): int
	{
		return 1;
	}

	public function testArrays()
	{
		/** @var string[] $a */
		$a = ['x'];

		/** @var int[] $b */
		$b = [1, 2];

		$this->assertSame($a, $b);
	}

	public function testLogicallyCorrectAssertSame()
	{
		$this->assertSame(1, 1);
		$this->assertSame(['a'], ['a', 'b']);
		$this->assertSame('1', '1');
		$this->assertSame('1', '2');
		$this->assertSame(new \stdClass(), new \stdClass());
		$this->assertSame('1', $this->returnsString());
		$this->assertSame(1, self::returnsInt());
		$this->assertSame(['a'], ['a', 1]);
		$this->assertSame(['a', 2, 3.0], ['a', 1]);
		self::assertSame(1, 2); // test self
		static::assertSame(1, 2); // test static
		parent::assertSame(1, 2); // test parent
	}

	public function testOther()
	{
		// assertEquals is not checked
		$this->assertEquals('1', 1);

		// only calls on \PHPUnit\Framework\TestCase are analyzed
		$foo = new \Dummy\Foo();
		$foo->assertSame();
	}

	public function testStaticMethodReturnWithSameTypeIsNotReported()
	{
		$this->assertSame(self::createSomething('foo'), self::createSomething('foo'));
		$this->assertNotSame(self::createSomething('bar'), self::createSomething('bar'));
	}

	/**
	 * @return object
	 */
	private static function createSomething(string $what)
	{
		return new \stdClass();
	}

}
