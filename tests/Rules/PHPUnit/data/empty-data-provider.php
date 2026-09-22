<?php

namespace EmptyDataProviderTest;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class BarTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	#[DataProvider('provideData')]
	public function testBar(): void
	{
		self::assertTrue(true);
	}

	public static function provideData(): iterable
	{
		return [];
	}

}

class NonEmptyTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	public function testFoo(string $input): void
	{
	}

	public static function provideData(): iterable
	{
		return [['a']];
	}

}

class GeneratorTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	public function testFoo(string $input): void
	{
	}

	public static function provideData(bool $flag = false): iterable
	{
		if ($flag) {
			yield ['a'];
		}
	}

}

class UnrelatedProviderTest extends TestCase
{

	public function testFoo(): void
	{
	}

	public static function provideData(): iterable
	{
		return [];
	}

}

class PartiallyEmptyTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	public function testFoo(string $input): void
	{
	}

	public static function provideData(bool $flag = false): iterable
	{
		if ($flag) {
			return [];
		}

		return [['a']];
	}

}

class AlwaysEmptyTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	public function testFoo(string $input): void
	{
	}

	public static function provideData(bool $flag = false): iterable
	{
		if ($flag) {
			return [];
		}

		return [];
	}

}

class UnknownTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	public function testFoo(string $input): void
	{
	}

	/**
	 * @return array<int, array{string}>
	 */
	public static function provideData(): iterable
	{
		return self::build();
	}

	/**
	 * @return array<int, array{string}>
	 */
	private static function build(): array
	{
		return [];
	}

}

class WrongCaseTest extends TestCase
{

	/**
	 * @dataProvider provideDATA
	 */
	public function testFoo(string $input): void
	{
	}

	public static function provideData(): iterable
	{
		return [];
	}

}

class AttributeOnlyTest extends TestCase
{

	#[DataProvider('provideData')]
	public function testFoo(string $input): void
	{
	}

	public static function provideData(): iterable
	{
		return [];
	}

}

class BareReturnTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	public function testFoo(string $input): void
	{
	}

	public static function provideData(bool $flag = false)
	{
		if ($flag) {
			return;
		}

		return [];
	}

}

class NoReturnTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	public function testFoo(string $input): void
	{
	}

	public static function provideData(): iterable
	{
	}

}

class NonIterableTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	public function testFoo(string $input): void
	{
	}

	public static function provideData(): string
	{
		return 'nope';
	}

}

class ExternalProviderHolder
{

	/**
	 * @return iterable<int, array{string}>
	 */
	public static function provideData(): iterable
	{
		return [['a']];
	}

}

class ExternalProviderTest extends TestCase
{

	/**
	 * @dataProvider \EmptyDataProviderTest\ExternalProviderHolder::provideData
	 */
	#[DataProviderExternal(ExternalProviderHolder::class, 'provideData')]
	public function testFoo(string $input): void
	{
	}

	public static function provideData(): iterable
	{
		return [];
	}

}

class UnknownProviderClassTest extends TestCase
{

	/**
	 * @dataProvider \EmptyDataProviderTest\ThisClassDoesNotExist::provideData
	 */
	public function testFoo(string $input): void
	{
	}

	public static function provideData(): iterable
	{
		return [];
	}

}

class MaybeIterableTest extends TestCase
{

	/**
	 * @dataProvider provideData
	 */
	public function testFoo(string $input): void
	{
	}

	/**
	 * @return array{}|string
	 */
	public static function provideData(bool $flag = false)
	{
		$data = $flag ? '' : [];

		return $data;
	}

}
