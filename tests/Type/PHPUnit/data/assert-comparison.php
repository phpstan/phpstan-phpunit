<?php

namespace AssertComparison;

use PHPUnit\Framework\TestCase;
use function PHPStan\Testing\assertType;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertLessThan;

class Foo extends TestCase
{

	/** @param string[] $arr */
	public function referenceAssertTrue(array $arr): void
	{
		self::assertTrue(count($arr) > 2);
		assertType('non-empty-array<string>', $arr);
	}

	/** @param string[] $arr */
	public function greaterThanStatic(array $arr): void
	{
		self::assertGreaterThan(2, count($arr));
		assertType('non-empty-array<string>', $arr);
	}

	/** @param string[] $arr */
	public function greaterThanMethod(array $arr): void
	{
		$this->assertGreaterThan(2, count($arr));
		assertType('non-empty-array<string>', $arr);
	}

	/** @param string[] $arr */
	public function greaterThanFunction(array $arr): void
	{
		assertGreaterThan(2, count($arr));
		assertType('non-empty-array<string>', $arr);
	}

	/** @param string[] $arr */
	public function lessThanTest(array $arr): void
	{
		self::assertLessThan(1, count($arr));
		assertType('array{}', $arr);
	}

	/** @param string[] $arr */
	public function lessThanFunction(array $arr): void
	{
		assertLessThan(1, count($arr));
		assertType('array{}', $arr);
	}

	public function integers(int $a, int $c): void
	{
		self::assertGreaterThan(5, $a);
		assertType('int<6, max>', $a);

		self::assertLessThan(5, $c);
		assertType('int<min, 4>', $c);
	}

}
