<?php

namespace PHPUnit\Framework;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;

class TestCase
{

	/**
	 * @template T
	 * @param class-string<T> $originalClassName
	 * @return MockObject&T
	 */
	public function createStub($originalClassName) {}

	/**
	 * @template T
	 * @param class-string<T> $originalClassName
	 * @return MockObject&T
	 */
	public function createMock($originalClassName) {}

	/**
	 * @template T
	 * @param class-string<T> $className
	 * @return MockBuilder<T>
	 */
	public function getMockBuilder(string $className) {}

	/**
	 * @template T
	 * @param class-string<T> $originalClassName
	 * @return MockObject&T
	 */
	public function createConfiguredMock($originalClassName) {}

	/**
	 * @template T
	 * @param class-string<T> $originalClassName
	 * @param string[] $methods
	 * @return MockObject&T
	 */
	public function createPartialMock($originalClassName, array $methods) {}

	/**
	 * @template T
	 * @param class-string<T> $originalClassName
	 * @return MockObject&T
	 */
	public function createTestProxy($originalClassName) {}

	/**
	 * @template T
	 * @param class-string<T> $originalClassName
	 * @param string $mockClassName
	 * @param bool $callOriginalConstructor
	 * @param bool $callOriginalClone
	 * @param bool $callAutoload
	 * @param string[] $mockedMethods
	 * @param bool $cloneArguments
	 * @return MockObject&T
	 */
	protected function getMockForAbstractClass($originalClassName, array $arguments = [], $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $mockedMethods = [], $cloneArguments = false) {}

	/**
	 * @template T
	 * @param string $wsdlFile
	 * @param class-string<T> $originalClassName
	 * @param string $mockClassName
	 * @param bool $callOriginalConstructor
	 * @param array $options
	 * @return MockObject&T
	 */
	protected function getMockFromWsdl($wsdlFile, $originalClassName = '', $mockClassName = '', array $methods = [], $callOriginalConstructor = true, array $options = []) {}

}
