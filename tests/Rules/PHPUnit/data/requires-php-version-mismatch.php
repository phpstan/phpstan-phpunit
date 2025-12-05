<?php

namespace RequiresPhpVersionMismatch;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;

class RequiresPhp5 extends TestCase
{
	#[RequiresPhp('< 7.0')]
	public function testFoo(): void {

	}
}

class RequiresPhp8 extends TestCase
{
	#[RequiresPhp('>=8.0')]
	public function testFoo(): void {

	}
}
