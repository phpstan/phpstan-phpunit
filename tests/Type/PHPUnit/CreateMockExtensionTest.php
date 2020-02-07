<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use ExampleTestCase\BarInterface;
use ExampleTestCase\FooInterface;
use Iterator;
use PHPUnit\Framework\MockObject\MockObject;

class CreateMockExtensionTest extends ExtensionTestCase
{

	/**
	 * @dataProvider getProvider
	 * @param string $expression
	 * @param string $type
	 */
	public function testCreateMock(string $expression, string $type): void
	{
		$this->processFile(
			__DIR__ . '/data/create-mock.php',
			$expression,
			$type,
			[new CreateMockDynamicReturnTypeExtension(), new GetMockBuilderDynamicReturnTypeExtension()]
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
