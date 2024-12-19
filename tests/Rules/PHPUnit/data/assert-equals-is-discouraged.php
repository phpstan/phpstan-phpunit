<?php

declare(strict_types=1);

namespace SameOverEqualsTest;

use Exception;
use PHPUnit\Framework\TestCase;

final class AssertSameOverAssertEqualsRule extends TestCase
{
	public function dummyTest(string $string, int $integer,	bool $boolean, float $float, ?string $nullableString): void
	{
		$null = null;

		$this->assertSame(5, $integer);
		static::assertSame(5, $integer);

		$this->assertEquals('', $string);
		$this->assertEquals(null, $string);
		static::assertEquals(null, $string);
		static::assertEquals($nullableString, $string);
		$this->assertEquals(2, $integer);
		$this->assertEquals(2.2, $float);
		static::assertEquals((int) '2', (int) $string);
		$this->assertEquals(true, $boolean);
		$this->assertEquals($string, $string);
		$this->assertEquals($integer, $integer);
		$this->assertEquals($boolean, $boolean);
		$this->assertEquals($float, $float);
		$this->assertEquals($null, $null);
		$this->assertEquals((string) new Exception(), (string) new Exception());
		$this->assertEquals([], []);
		$this->assertEquals(new Exception(), new Exception());
		static::assertEquals(new Exception(), new Exception());

		$this->assertNotEquals($string, $string);
		$this->assertNotEquals($integer, $integer);
		$this->assertNotEquals($boolean, $boolean);
		$this->assertNotSame(5, $integer);
		static::assertNotSame(5, $integer);
	}
}
