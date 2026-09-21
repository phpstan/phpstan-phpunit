<?php declare(strict_types = 1);

namespace AssertEmptyIsDiscouragedTest;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class AssertEmptyTest extends TestCase
{

	public function test(): void
	{
		$this->assertEmpty([]);
		$this->assertNotEmpty([1]);
		Assert::assertEmpty([]);
		static::assertNotEmpty([1]);
	}

}
