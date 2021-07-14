<?php

namespace AssertFunction;

use function PHPStan\Testing\assertType;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertInstanceOf;

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

}
