<?php

namespace AssertFunction;

use function PHPStan\Testing\assertType;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertContainsEquals;
use function PHPUnit\Framework\assertContainsOnlyInstancesOf;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertObjectHasAttribute;
use function PHPUnit\Framework\assertObjectHasProperty;

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

	public function objectHasProperty(object $a): void
	{
		assertObjectHasProperty('property', $a);
		assertType("object&hasProperty(property)", $a);
	}

	public function testEmpty($a): void
	{
		assertEmpty($a);
		assertType("0|0.0|''|'0'|array{}|Countable|EmptyIterator|false|null", $a);
	}

	public function contains(array $a, \Traversable $b): void
	{
		assertContains('foo', $a);
		assertType('non-empty-array', $a);

		assertContains('foo', $b);
		assertType('Traversable', $b);
	}

	public function containsEquals(array $a, \Traversable $b): void
	{
		assertContainsEquals('foo', $a);
		assertType('non-empty-array', $a);

		assertContainsEquals('foo', $b);
		assertType('Traversable', $b);
	}

	public function containsOnlyInstancesOf(array $a, \Traversable $b): void
	{
		assertContainsOnlyInstancesOf(\stdClass::class, $a);
		assertType('array<stdClass>', $a);

		assertContainsOnlyInstancesOf(\stdClass::class, $b);
		assertType('Traversable', $b);
	}

}
