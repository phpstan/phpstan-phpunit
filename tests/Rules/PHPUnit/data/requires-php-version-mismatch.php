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

class RequiresPhp5Caret extends TestCase
{
	#[RequiresPhp('^5.0')]
	public function testFoo(): void {

	}
}

class RequiresPhp5Tilde extends TestCase
{
	#[RequiresPhp('~5.0')]
	public function testFoo(): void {

	}
}

class RequiresPhp5Star extends TestCase
{
	#[RequiresPhp('5.*')]
	public function testFoo(): void {

	}
}

class RequiresPhp8 extends TestCase
{
	#[RequiresPhp('>=8.0')]
	public function testFoo(): void {

	}
}

class RequiresPhp8Caret extends TestCase
{
	#[RequiresPhp('^8.0')]
	public function testFoo(): void {

	}
}

class RequiresPhp8Tilde extends TestCase
{
	#[RequiresPhp('~8.0')]
	public function testFoo(): void {

	}
}

class RequiresPhp8Star extends TestCase
{
	#[RequiresPhp('8.*')]
	public function testFoo(): void {

	}
}
