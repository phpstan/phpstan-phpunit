<?php

namespace RequiresPhpVersion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;

class DeprecatedVersionFormat extends TestCase
{
	#[RequiresPhp('8.0')]
	public function testDeprecatedFormat(): void {

	}
}

class AllGoodTest extends TestCase
{
	#[RequiresPhp('>=8.0')]
	public function testHappyPath(): void {

	}
}
