<?php // lint >= 8.1

namespace MissingParentMethodCallsFirstClassCallable;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
	public function setUp(): void
	{
	}

	public function tearDown(): void
	{
	}
}

class FirstClassCallableTest extends BaseTestCase
{
	public function setUp(): void
	{
		$callback = parent::setUp(...);
	}

	public function tearDown(): void
	{
		$callback = parent::tearDown(...);
	}
}

class FirstClassCallableInvokedTest extends BaseTestCase
{
	public function setUp(): void
	{
		$callback = parent::setUp(...);
		parent::setUp();
	}
}
