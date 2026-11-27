<?php

namespace RequiresPhpVersionNonString;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;
use PHPUnit\Framework\Attributes\RequiresPhpunit;

// https://github.com/phpstan/phpstan-phpunit/issues/364
class IntArgument extends TestCase
{
	#[RequiresPhp(8)]
	public function testFoo(): void {

	}
}

class FloatArgument extends TestCase
{
	#[RequiresPhp(8.0)]
	public function testFoo(): void {

	}
}

class ArrayArgument extends TestCase
{
	#[RequiresPhp(['8.0'])]
	public function testFoo(): void {

	}
}

class NamedArgument extends TestCase
{
	#[RequiresPhp(versionRequirement: '>=8.0')]
	public function testFoo(): void {

	}
}

class PhpunitIntArgument extends TestCase
{
	#[RequiresPhpunit(11)]
	public function testFoo(): void {

	}
}

#[RequiresPhp(8)]
class ClassIntArgument extends TestCase
{
	public function testFoo(): void {

	}
}

#[RequiresPhpunit(versionRequirement: '>=11.0')]
class ClassNamedArgument extends TestCase
{
	public function testFoo(): void {

	}
}
