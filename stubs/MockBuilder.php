<?php

namespace PHPUnit\Framework\MockObject;

use PHPUnit\Framework\TestCase;

/**
 * @template TMockedClass
 */
class MockBuilder
{

	/**
	 * @param TestCase $testCase
	 * @param class-string<TMockedClass> $type
	 */
	public function __construct(TestCase $testCase, $type) {}

	/**
	 * @return MockObject&TMockedClass
	 */
	public function getMock() {}

	/**
	 * @return MockObject&TMockedClass
	 */
	public function getMockForAbstractClass() {}

}
