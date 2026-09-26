<?php declare(strict_types = 1);

namespace MockObjectTypeNode;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockObject as Mock;
use PHPUnit\Framework\MockObject\Stub;
use function PHPStan\Testing\assertType;

class Foo
{

}

/**
 * @phpstan-type FooMock MockObject
 */
class Test
{

	/**
	 * @param MockObject|Foo $a
	 * @param \PHPUnit\Framework\MockObject\MockObject|Foo $b
	 * @param Mock|Foo $c
	 * @param Stub|Foo $d
	 * @param FooMock|Foo $e
	 * @param (MockObject|Foo)|null $f
	 * @param Foo|int $g
	 */
	public function mockUnions($a, $b, $c, $d, $e, $f, $g): void
	{
		assertType('MockObjectTypeNode\\Foo&PHPUnit\\Framework\\MockObject\\MockObject', $a);
		assertType('MockObjectTypeNode\\Foo&PHPUnit\\Framework\\MockObject\\MockObject', $b);
		assertType('MockObjectTypeNode\\Foo&PHPUnit\\Framework\\MockObject\\MockObject', $c);
		assertType('MockObjectTypeNode\\Foo&PHPUnit\\Framework\\MockObject\\Stub', $d);
		assertType('MockObjectTypeNode\\Foo&PHPUnit\\Framework\\MockObject\\MockObject', $e);
		assertType('(MockObjectTypeNode\\Foo&PHPUnit\\Framework\\MockObject\\MockObject)|null', $f);
		assertType('int|MockObjectTypeNode\\Foo', $g);
	}

	/**
	 * Each nesting level used to resolve its inner union twice, so this took 2^30 steps.
	 *
	 * @param (((((((((((((((((((((((((((((int|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string $a
	 * @param (((((((((((((((((((((((((((((MockObject|Foo)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null $b
	 */
	public function deeplyNestedUnions($a, $b): void
	{
		assertType('int|string', $a);
		assertType('(MockObjectTypeNode\\Foo&PHPUnit\\Framework\\MockObject\\MockObject)|null', $b);
	}

}
