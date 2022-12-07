<?php declare(strict_types=1);

namespace ExampleTestCase;

class FooTestCase extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider provideBar Comment.
	 * @dataProvider providebaz
	 * @dataProvider provideQux
	 * @dataProvider provideQuux
	 */
	public function testIsNotFoo(string $subject): void
	{
		self::assertNotSame('foo', $subject);
	}

	public static function provideBar(): iterable
	{
		return [
			['bar'],
		];
	}

	public static function provideBaz(): iterable
	{
		return [
			['baz'],
		];
	}

	public function provideQux(): iterable
	{
		return [
			['qux'],
		];
	}

	protected static function provideQuux(): iterable
	{

		return [
			['quux'],
		];
	}
}

trait BarProvider
{
	public static function provideCorge(): iterable
	{
		return [
			['corge'],
		];
	}
}

class BarTestCase extends \PHPUnit\Framework\TestCase
{
	use BarProvider;

	/**
	 * @dataProvider provideNonExisting
	 * @dataProvider provideCorge
	 */
	public function testIsNotBar(string $subject): void
	{
		self::assertNotSame('bar', $subject);
	}
}

class FooBarTestCase extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider provideArray
	 * @dataProvider provideIterator
	 * @dataProvider provideMultiple
	 */
	public function testIsNotFooBar(string $subject): void
	{
		self::assertNotSame('foo', $subject);
	}

	/**
	 * @dataProvider provideArray
	 * @dataProvider provideIterator
	 *
	 * @param string $subject
	 */
	public function testIsNotFooBarPhpDoc($subject): void
	{
		self::assertNotSame('foo', $subject);
	}


	/**
	 * @dataProvider provideArray
	 * @dataProvider provideIterator
	 * @dataProvider provideMultiple
	 */
	public function testIsFooBar(int $subject): void
	{
		self::assertNotSame(123, $subject);
	}

	/**
	 * @dataProvider provideMultiple
	 */
	public function testMultipleParams(string $subject, int $i): void
	{
	}

	/**
	 * @dataProvider provideMultiple
	 */
	public function testBogusMultipleParams(float $subject, string $i): void
	{
	}


	/**
	 * @return list<array<string>>
	 */
	public static function provideArray(): iterable
	{
		return [
			['bar'],
		];
	}

	/**
	 * @return \Iterator<list<string>>
	 */
	public static function provideIterator(): \Iterator
	{
		yield ['bar'];
	}

	/**
	 * @return \Iterator<array{string, int}>
	 */
	public static function provideMultiple(): \Iterator
	{
		yield ['bar', 1];
	}
}
