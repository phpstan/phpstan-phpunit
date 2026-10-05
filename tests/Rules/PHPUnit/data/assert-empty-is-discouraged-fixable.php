<?php declare(strict_types = 1);

namespace AssertEmptyIsDiscouragedFixTest;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class AssertEmptyTest extends TestCase
{

	/**
	 * @param non-falsy-string $nonFalsyString
	 * @param non-falsy-string $otherNonFalsyString
	 * @param string|int $union
	 */
	public function test(
		bool $boolean,
		array $array,
		int $integer,
		float $float,
		string $string,
		$union,
		string $nonFalsyString,
		string $otherNonFalsyString,
		?\stdClass $nullableObject,
		?object $otherNullableObject
	): void
	{
		$this->assertEmpty($boolean);
		$this->assertNotEmpty($boolean);
		$this->assertEmpty($array, 'message');
		$this->assertNotEmpty($array);
		$this->assertEmpty($integer);
		$this->assertNotEmpty($integer);
		Assert::assertEmpty($float);
		Assert::assertNotEmpty($float);
		static::assertEmpty(null);
		static::assertNotEmpty(null);
		$this->assertEmpty($nonFalsyString);
		$this->assertNotEmpty($otherNonFalsyString);
		$this->assertEmpty($string);
		$this->assertNotEmpty($string);
		$this->assertEmpty($union);
		$this->assertNotEmpty($union);
		$this->assertEmpty($nullableObject);
		$this->assertNotEmpty($otherNullableObject);
	}

}
