<?php

namespace MissingParentMethodCalls;

use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{
	public function setUp(): void
	{
		$this->foo = true;
	}
}

class BaseTestCase extends TestCase
{
	public function setUp(): void
	{
		$this->bar = true;
	}

	public function tearDown(): void
	{
		$this->bar = null;
	}
}

class BazTest extends BaseTestCase
{
	private $baz;

	public function setUp(): void
	{
		$this->baz = true;
	}

	public function baz(): bool
	{
		return $this->baz;
	}
}

class BarBazTest extends BaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->barBaz = true;
	}
}

class FooBarBazTest extends BaseTestCase
{
	public function setUp(): void
	{
		$result = 1 + 1;
		parent::setUp();

		$this->fooBarBaz = $result;
	}

	public function tearDown(): void
	{
		$this->fooBarBaz = null;
	}
}

class NormalBaseClass {}

class NormalClass extends NormalBaseClass
{
	public function setUp()
	{
		return true;
	}
}
