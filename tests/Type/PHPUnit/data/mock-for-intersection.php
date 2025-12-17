<?php

namespace MockForIntersection;

use PHPUnit\Framework\TestCase;

use function PHPStan\Testing\assertType;

class Foo extends TestCase
{

	public function inheritedAssertMethodsNarrowType(?string $s): void
	{
		assertType(
			'MockForIntersection\BarInterface&MockForIntersection\FooInterface&PHPUnit\Framework\MockObject\MockObject',
			$this->createMockForIntersectionOfInterfaces([FooInterface::class, BarInterface::class]),
		);
		assertType(
			'MockForIntersection\BarInterface&MockForIntersection\FooInterface&PHPUnit\Framework\MockObject\Stub',
			self::createStubForIntersectionOfInterfaces([FooInterface::class, BarInterface::class]),
		);
	}

}

interface FooInterface {}
interface BarInterface {}
