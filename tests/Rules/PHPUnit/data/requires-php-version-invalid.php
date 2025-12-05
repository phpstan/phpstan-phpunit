<?php

namespace RequiresPhpVersionMismatch;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;

class InvalidConstraint extends TestCase
{
	#[RequiresPhp('abc')]
	public function testFoo(): void {

	}
}

