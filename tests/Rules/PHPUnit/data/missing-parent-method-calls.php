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
		parent::tearDown();

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

abstract class BaseTestWithoutSetUp extends TestCase
{

}

class LoremTest extends BaseTestWithoutSetUp
{

	protected function setUp(): void
	{
		// parent call is not missing here
	}

}

class ParentCallInIfTest extends BaseTestCase
{
	private bool $enabled = true;

	public function setUp(): void
	{
		if ($this->enabled) {
			parent::setUp();
		}
	}

	public function tearDown(): void
	{
		if ($this->enabled) {
			parent::tearDown();
		} else {
			parent::tearDown();
		}
	}
}

class ParentCallInTryFinallyTest extends BaseTestCase
{
	private ?object $resource = null;

	public function setUp(): void
	{
		try {
			parent::setUp();
		} catch (\Throwable $e) {
			throw $e;
		}
	}

	public function tearDown(): void
	{
		try {
			$this->resource = null;
		} finally {
			parent::tearDown();
		}
	}
}

class ParentCallInClosureTest extends BaseTestCase
{
	public function setUp(): void
	{
		$fn = function (): void {
			parent::setUp();
		};
	}

	public function tearDown(): void
	{
		$fn = fn () => parent::tearDown();
	}
}

class ParentCallInAnonymousClassTest extends BaseTestCase
{
	public function setUp(): void
	{
		new class extends BaseTestCase {
			public function setUp(): void
			{
				parent::setUp();
			}
		};
	}
}
