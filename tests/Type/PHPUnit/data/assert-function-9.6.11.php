<?php

namespace AssertFunction96;

use function PHPStan\Testing\assertType;
use function PHPUnit\Framework\assertObjectHasProperty;

class Foo
{

	public function objectHasProperty(object $a): void
	{
		assertObjectHasProperty('property', $a);
		assertType("object&hasProperty(property)", $a);
	}

}
