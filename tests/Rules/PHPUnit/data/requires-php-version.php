<?php

namespace RequiresPhpVersion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;

class TwoDigitVersionA extends TestCase
{
	#[RequiresPhp('8.0')]
	public function testFoo(): void {

	}
}

class TwoDigitVersionB extends TestCase
{
	#[RequiresPhp('>=8.0')]
	public function testBar(): void {

	}
}

class CorrectRequirement extends TestCase
{
	#[RequiresPhp('>=8.0.0')]
	public function testBar(): void {

	}
}
