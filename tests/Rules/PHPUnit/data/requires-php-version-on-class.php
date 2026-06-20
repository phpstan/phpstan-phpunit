<?php

namespace RequiresPhpVersionOnClass;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;

#[RequiresPhp('< 7.0')]
class RequiresPhpOnClass extends TestCase
{
	public function testBar(): void {

	}
}

#[RequiresPhp('>=8.0.0')]
class CorrectRequirementOnClass extends TestCase
{
	public function testBar(): void {

	}
}
