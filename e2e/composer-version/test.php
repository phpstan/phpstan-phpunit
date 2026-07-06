<?php

use PHPUnit\Framework\Attributes\RequiresPhp;

class A extends \PHPUnit\Framework\TestCase {
	#[RequiresPhp('<=8.2.0')]
	public function testFoo() {}
}

class B extends \PHPUnit\Framework\TestCase {
	#[RequiresPhp('<=8.0.0')]
	public function testFoo() {}
}

class C extends \PHPUnit\Framework\TestCase {
	#[RequiresPhp('^8.0.0')]
	public function testFoo() {}
}

class D extends \PHPUnit\Framework\TestCase {
	#[RequiresPhp('^8.1.0')]
	public function testFoo() {}
}
