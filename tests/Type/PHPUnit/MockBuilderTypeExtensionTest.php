<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use ExampleTestCase\BarInterface;
use ExampleTestCase\FooInterface;
use Iterator;
use PHPUnit\Framework\MockObject\MockObject;

class MockBuilderTypeExtensionTest extends ExtensionTestCase
{

	/**
	 * @dataProvider getProvider
	 * @param string $expression
	 * @param string $type
	 */
	public function testMockBuilder(string $expression, string $type): void
	{
		$this->processFile(
			__DIR__ . '/data/mock-builder.php',
			$expression,
			$type,
			[new MockBuilderDynamicReturnTypeExtension(), new GetMockBuilderDynamicReturnTypeExtension()]
		);
	}

	/**
	 * @return Iterator<mixed>
	 */
	public function getProvider(): Iterator
	{
		yield ['$simpleInterface', implode('&', [FooInterface::class, MockObject::class])];
		yield ['$doubleInterface', implode('&', [BarInterface::class, FooInterface::class, MockObject::class])];
	}

}
