<?php // lint >= 8.0

namespace AssertEmptyIsDiscouragedNativeUnionTest;

use PHPUnit\Framework\TestCase;

final class UserDefinedObject
{

}

final class AssertEmptyTest extends TestCase
{

	public function test(
		string|int $value,
		array|bool $otherValue,
		\stdClass|int|null $nullableUnion,
		UserDefinedObject|int|null $userDefinedNullableUnion,
		bool $boolean,
		int $integer
	): void
	{
		$this->assertEmpty($value);
		$this->assertNotEmpty($otherValue);
		$this->assertEmpty($nullableUnion);
		$this->assertEmpty($userDefinedNullableUnion);
		$this->assertNotEmpty($userDefinedNullableUnion);
		$this->assertEmpty(actual: $boolean);
		$this->assertEmpty(message: 'message', actual: $integer);
	}

}
