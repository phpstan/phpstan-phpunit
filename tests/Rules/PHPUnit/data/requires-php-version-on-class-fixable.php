<?php

namespace RequiresPhpVersionOnClassFixable;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;
use PHPUnit\Framework\Attributes\RequiresPhpunit;

#[RequiresPhp('>=8.0')]
#[RequiresPhpunit('^11.0')]
class FixableOnClassTest extends TestCase
{
	public function testBar(): void {

	}
}

#[RequiresPhp('< 7.0')]
class NotFixableOnClassTest extends TestCase
{
	public function testBar(): void {

	}
}
