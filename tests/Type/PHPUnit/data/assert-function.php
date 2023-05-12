<?php

namespace AssertFunction;

use function PHPStan\Testing\assertType;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertObjectHasAttribute;

class Foo
{

	/**
	 * @param object $o
	 */
	public function doFoo($o): void
	{
		assertInstanceOf(self::class, $o);
		assertType(self::class, $o);
	}

	/**
	 * @template T of object
	 * @param object $o
	 * @param class-string<\DateTimeInterface> $class
	 */
	public function assertInstanceOfWorksWithTemplate($o, $class): void
	{
		assertInstanceOf($class, $o);
		assertType(\DateTimeInterface::class, $o);
	}

	public function arrayHasNumericKey(array $a, \ArrayAccess $b): void {
		assertArrayHasKey(0, $a);
		assertType('array&hasOffset(0)', $a);

		assertArrayHasKey(0, $b);
		assertType('ArrayAccess', $b);
	}

	public function arrayHasStringKey(array $a, \ArrayAccess $b): void
	{
		assertArrayHasKey('key', $a);
		assertType("array&hasOffset('key')", $a);

		assertArrayHasKey('key', $b);
		assertType("ArrayAccess", $b);
	}

	public function objectHasAttribute(object $a): void
	{
		assertObjectHasAttribute('property', $a);
		assertType("object&hasProperty(property)", $a);
	}

	public function testEmpty($a): void
	{
		assertEmpty($a);
		assertType("0|0.0|''|'0'|array{}|Countable|EmptyIterator|false|null", $a);
	}

}
