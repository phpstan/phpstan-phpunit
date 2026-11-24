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

	/** @param 1.5|5 $a */
	public function infiniteExpectedOrEqual($a): void
	{
		self::assertGreaterThanOrEqual(-INF, $a);
		self::assertLessThanOrEqual(INF, $a);
		assertType('1.5|5', $a);
	}

	/** @param 1.5|5 $a */
	public function infiniteActualOrEqual($a): void
	{
		self::assertGreaterThanOrEqual($a, INF);
		self::assertLessThanOrEqual($a, -INF);
		assertType('1.5|5', $a);
	}

	public function neverInfiniteOrEqual(int $a, int $b, int $c, int $d): void
	{
		self::assertGreaterThanOrEqual(INF, $a);
		assertType('*NEVER*', $a);

		self::assertLessThanOrEqual(-INF, $b);
		assertType('*NEVER*', $b);

		self::assertGreaterThanOrEqual($c, -INF);
		assertType('*NEVER*', $c);

		self::assertLessThanOrEqual($d, INF);
		assertType('*NEVER*', $d);
	}

	/**
	 * @param int<min, -1> $a
	 * @param int<1, max> $b
	 * @param int<1, max> $c
	 * @param int<min, -1> $d
	 */
	public function neverNanOrEqual(int $a, int $b, int $c, int $d): void
	{
		$nan = NAN;
		self::assertGreaterThanOrEqual($nan, $a);
		assertType('*NEVER*', $a);

		$nan = NAN;
		self::assertLessThanOrEqual($nan, $b);
		assertType('*NEVER*', $b);

		$nan = NAN;
		self::assertGreaterThanOrEqual($c, $nan);
		assertType('*NEVER*', $c);

		$nan = NAN;
		self::assertLessThanOrEqual($d, $nan);
		assertType('*NEVER*', $d);
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

	/**
	 * @param int|string $string
	 * @param int|numeric-string $numericString
	 * @param int|array<int> $array
	 * @param int|\stdClass $object
	 * @param mixed $mixed
	 */
	public function notNarrowedActualTypesOrEqual($string, $numericString, $array, $object, $mixed): void
	{
		self::assertGreaterThanOrEqual(5, $string);
		self::assertLessThanOrEqual(5, $string);
		assertType('int|string', $string);

		self::assertGreaterThanOrEqual(5, $numericString);
		self::assertLessThanOrEqual(5, $numericString);
		assertType('int|numeric-string', $numericString);

		self::assertGreaterThanOrEqual(5, $array);
		self::assertLessThanOrEqual(5, $array);
		assertType('array<int>|int', $array);

		self::assertGreaterThanOrEqual(5, $object);
		self::assertLessThanOrEqual(5, $object);
		assertType('int|stdClass', $object);

		self::assertGreaterThanOrEqual(5, $mixed);
		self::assertLessThanOrEqual(5, $mixed);
		assertType('mixed', $mixed);
	}

	/**
	 * @param int|array<int> $array
	 * @param mixed $mixed
	 */
	public function notNarrowedExpectedTypesOrEqual($array, $mixed): void
	{
		self::assertGreaterThanOrEqual($array, 5);
		self::assertLessThanOrEqual($array, 5);
		assertType('array<int>|int', $array);

		self::assertGreaterThanOrEqual($mixed, 5);
		self::assertLessThanOrEqual($mixed, 5);
		assertType('mixed', $mixed);
	}
}
