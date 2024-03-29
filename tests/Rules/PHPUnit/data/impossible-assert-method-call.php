<?php

namespace ImpossibleAssertMethodCall;

use Countable;
use PHPUnit\Framework\TestCase;

class Foo extends TestCase
{

	public function doFoo(Countable $c): void
	{
		$this->assertEmpty($c);
		$this->assertEmpty([]);
		$this->assertEmpty([1, 2, 3]);
	}

	public function doBar(object $o): void
	{
		$this->assertEmpty($o);
	}

	/**
	 * @param class-string<\Exception> $name
	 * @return void
	 */
	public function doBaz(\Exception $e, string $name): void
	{
		$this->assertInstanceOf($name, $e);
	}

}
