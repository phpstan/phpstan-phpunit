<?php declare(strict_types=1);

namespace ExampleTestCase;

use \PHPUnit\Framework\Attributes\DataProvider;

class FooTestCase extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider provideBar Comment.
	 * @dataProvider providebaz
	 * @dataProvider provideQux
	 * @dataProvider provideQuux
	 * @dataProvider \ExampleTestCase\BarTestCase::provideToOtherClass
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
	 * @dataProvider NonExisting::provideNonExisting
	 * @dataProvider provideCorge
	 */
	public function testIsNotBar(string $subject): void
	{
		self::assertNotSame('bar', $subject);
	}

	public static function provideToOtherClass(): iterable
	{
		return [
			['toOtherClass'],
		];
	}
}

class BazTestCase extends \PHPUnit\Framework\TestCase
{
	#[\PHPUnit\Framework\Attributes\DataProvider('provideNonExisting')]
	#[DataProvider('provideNonExisting2')]
	#[\PHPUnit\Framework\Attributes\DataProviderExternal('\\ExampleTestCase\\BarTestCase', 'providetootherclass')]
	#[\PHPUnit\Framework\Attributes\DataProviderExternal(\ExampleTestCase\BarTestCase::class, 'providetootherclass')]
	public function testIsNotBaz(string $subject): void
	{
		self::assertNotSame('baz', $subject);
	}
}
