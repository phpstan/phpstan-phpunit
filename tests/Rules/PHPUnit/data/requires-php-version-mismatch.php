<?php

namespace RequiresPhpVersionMismatch;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;

class RequiresPhpLowerEqual85a extends TestCase
{
	#[RequiresPhp('<= 8.5')] // note, version without patch component
	public function testFoo(): void {

	}
}

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

class RequiresPhpLowerEqual84 extends TestCase
{
	#[RequiresPhp('<= 8.4')]
	public function testFoo(): void {

	}
}

class RequiresPhpLowerEqual85 extends TestCase
{
	#[RequiresPhp('<= 8.5')]
	public function testFoo(): void {

	}
}

class RequiresPhp83 extends TestCase
{
	#[RequiresPhp('8.3.*')]
	public function testFoo(): void {

	}
}

class RequiresPhp85 extends TestCase
{
	#[RequiresPhp('8.5.*')]
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

class RequiresPhpLowerEqual853Digits extends TestCase
{
	#[RequiresPhp('<= 8.5.0')]
	public function testFoo(): void {

	}
}
