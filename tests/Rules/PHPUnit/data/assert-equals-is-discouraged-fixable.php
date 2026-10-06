<?php

declare(strict_types=1);

namespace SameAssertEqualsTestFix;

use PHPUnit\Framework\TestCase;

class Foo extends TestCase
{

	public function doFoo(string $s, string $t): void
	{
		$this->assertEquals('', $s);
		$this->assertNotEquals('', $t);
		$this->assertEquals(expected: '', actual: $s);
		$this->assertEquals('', $s, ...func_get_args());
	}

	public function doFoo2(string $s, string $t): void
	{
		self::assertEquals('', $s);
		self::assertNotEquals('', $t);
	}

}
