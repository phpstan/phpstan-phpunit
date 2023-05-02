<?php

namespace AssertFunction;

use function PHPStan\Testing\assertType;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertContainsOnlyInstancesOf;
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

	public function arrayHasNumericKey(array $a): void {
		assertArrayHasKey(0, $a);
		assertType('array&hasOffset(0)', $a);
	}

	public function arrayHasStringKey(array $a): void
	{
		assertArrayHasKey('key', $a);
		assertType("array&hasOffset('key')", $a);
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

	public function containsOnlyInstancesOf(array $a, \Traversable $b): void
	{
		assertContainsOnlyInstancesOf(\stdClass::class, $a);
		assertType('array<stdClass>', $a);

		assertContainsOnlyInstancesOf(\stdClass::class, $b);
		assertType('Traversable', $b);
	}

}
