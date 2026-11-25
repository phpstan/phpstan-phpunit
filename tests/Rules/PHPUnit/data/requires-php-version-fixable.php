<?php

namespace RequiresPhpVersionFixable;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;
use PHPUnit\Framework\Attributes\RequiresPhpunit;

class FixableTest extends TestCase
{
	#[RequiresPhp('8')]
	public function testMajorOnly(): void {

	}

	#[RequiresPhp('8.0')]
	public function testMissingOperator(): void {

	}

	// not reported, as it is not numeric
	#[RequiresPhp('8.0.1')]
	public function testCompleteMissingOperator(): void {

	}

	#[RequiresPhp('>=8.0')]
	public function testGreaterOrEqual(): void {

	}

	#[RequiresPhp('>= 8')]
	public function testGreaterOrEqualWithSpace(): void {

	}

	#[RequiresPhp('^8.0')]
	public function testCaret(): void {

	}

	#[RequiresPhp("8.0")]
	public function testDoubleQuoted(): void {

	}

	#[RequiresPhp('8.0'), RequiresPhpunit('>=11')]
	public function testMultipleAttributes(): void {

	}

	#[RequiresPhp('>= 8.0.0')]
	#[RequiresPhp('8.1')]
	public function testRepeatedAttribute(): void {

	}

	// changing these would change their meaning
	#[RequiresPhp('<8.0')]
	public function testLessThan(): void {

	}

	#[RequiresPhp('>8.0')]
	public function testGreaterThan(): void {

	}

	#[RequiresPhp('~8.0')]
	public function testTilde(): void {

	}

	#[RequiresPhp('<=8.5')]
	public function testLessOrEqual(): void {

	}
}
