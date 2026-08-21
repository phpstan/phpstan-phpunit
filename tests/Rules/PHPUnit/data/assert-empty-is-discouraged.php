<?php declare(strict_types = 1);

namespace AssertEmptyIsDiscouragedTest;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertNotEmpty;

final class AssertEmptyTest extends TestCase
{

	public function test(): void
	{
		$this->assertEmpty([]);
		$this->assertNotEmpty([1]);
		Assert::assertEmpty([]);
		static::assertNotEmpty([1]);
		assertEmpty([]);
		assertNotEmpty([1]);
	}

}
