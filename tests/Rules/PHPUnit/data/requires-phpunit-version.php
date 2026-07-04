<?php

namespace RequiresPhpunitVersion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhpunit;

class TwoDigitVersionA extends TestCase
{
	#[RequiresPhpunit('11.0')]
	public function testFoo(): void {

	}
}

#[RequiresPhpunit('>=11.0')]
class TwoDigitVersionB extends TestCase
{
	public function testBar(): void {

	}
}

class CorrectRequirement extends TestCase
{
	#[RequiresPhpunit('>=11.0.0')]
	public function testBar(): void {

	}

	#[RequiresPhpunit('^12.0.0')]
	public function testBaz(): void {

	}
}

#[RequiresPhpunit('>=11.0.0')]
class CorrectClassRequirement extends TestCase
{
	public function testBar(): void {

	}
}
