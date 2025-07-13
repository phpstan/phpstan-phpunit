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
	}

	public function doFoo2(string $s, string $t): void
	{
		self::assertEquals('', $s);
		self::assertNotEquals('', $t);
	}

}
