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
	}

}
