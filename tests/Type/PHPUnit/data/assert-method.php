<?php

namespace AssertMethod;

use PHPUnit\Framework\TestCase;
use function PHPStan\Testing\assertType;

class Foo
{

	public function inheritedAssertMethodsNarrowType(?string $s): void
	{
		$customAsserter = new class () extends \PHPUnit\Framework\Assert {};
		$customAsserter->assertNotNull($s);
		assertType('string', $s);
	}
}

class FooBar extends TestCase
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
	public function lessThanTest(array $arr): void
	{
		self::assertLessThan(1, count($arr));
		assertType('array{}', $arr);
	}

	/** @param string[] $arr */
	public function greaterThanOrEqualStatic(array $arr): void
	{
		self::assertGreaterThanOrEqual(1, count($arr));
		assertType('non-empty-array<string>', $arr);
	}

	/** @param string[] $arr */
	public function lessThanOrEqualTest(array $arr): void
	{
		self::assertLessThanOrEqual(0, count($arr));
		assertType('array{}', $arr);
	}

	public function integers(int $a, int $c): void
	{
		self::assertGreaterThan(5, $a);
		assertType('int<6, max>', $a);

		self::assertLessThan(5, $c);
		assertType('int<min, 4>', $c);
	}

	public function integersOrEqual(int $a, int $c): void
	{
		self::assertGreaterThanOrEqual(5, $a);
		assertType('int<5, max>', $a);

		self::assertLessThanOrEqual(5, $c);
		assertType('int<min, 5>', $c);
	}

	/**
	 * @param 1|5|9.5 $a
	 * @param 1.5|5|9 $c
	 */
	public function integersAndFloatsOrEqual($a, $c): void
	{
		self::assertGreaterThanOrEqual(5.0, $a);
		assertType('5|9.5', $a);

		self::assertLessThanOrEqual(5.0, $c);
		assertType('1.5|5', $c);
	}

	public function notNarrowedActualOrEqual(?int $a, ?int $c): void
	{
		self::assertGreaterThanOrEqual(1, $a);
		assertType('int|null', $a);

		self::assertLessThanOrEqual(1, $c);
		assertType('int|null', $c);
	}

	public function notNarrowedExpectedOrEqual(int $a, int $c): void
	{
		self::assertGreaterThanOrEqual('5', $a);
		assertType('int', $a);

		self::assertLessThanOrEqual('5', $c);
		assertType('int', $c);
	}
}
