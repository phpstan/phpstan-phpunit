<?php declare(strict_types = 1);

namespace AssertEmptyIsDiscouragedNativeUnionTest;

use PHPUnit\Framework\TestCase;

final class AssertEmptyTest extends TestCase
{

	public function test(string|int $value, array|bool $otherValue): void
	{
		$this->assertEmpty($value);
		$this->assertNotEmpty($otherValue);
	}

}
