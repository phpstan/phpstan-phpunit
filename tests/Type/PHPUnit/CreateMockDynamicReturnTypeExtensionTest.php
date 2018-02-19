<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

class CreateMockDynamicReturnTypeExtensionTest extends \PHPStan\Type\PHPUnit\ExtensionTestCase
{

	public function createMockProvider(): array
	{
		return [
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$a',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$b',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$c',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$d',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$e',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$f',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$g',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$h',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$i',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$j',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$k',
			],
			[
				'CreateMockTest\MockedClass&PHPUnit\Framework\MockObject\MockObject',
				'$l',
			],
			[
				'CreateMockTest\TestClass&PHPUnit\Framework\MockObject\MockObject',
				'$m',
			],
			[
				'CreateMockTest\TestClass&PHPUnit\Framework\MockObject\MockObject',
				'$n',
			],
			[
				'CreateMockTest\TestClass&PHPUnit\Framework\MockObject\MockObject',
				'$o',
			],
			[
				'CreateMockTest\TestClass&PHPUnit\Framework\MockObject\MockObject',
				'$p',
			],
			[
				'CreateMockTest\TestClass&PHPUnit\Framework\MockObject\MockObject',
				'$q',
			],
			[
				'CreateMockTest\TestClass&PHPUnit\Framework\MockObject\MockObject',
				'$r',
			],
			[
				'PHPUnit\Framework\MockObject\MockObject',
				'$s',
			],
			[
				'PHPUnit\Framework\MockObject\MockObject',
				'$t',
			],
			[
				'PHPUnit\Framework\MockObject\MockObject',
				'$u',
			],
			[
				'PHPUnit\Framework\MockObject\MockObject',
				'$v',
			],
			[
				'PHPUnit\Framework\MockObject\MockObject',
				'$w',
			],
			[
				'PHPUnit\Framework\MockObject\MockObject',
				'$x',
			],
		];
	}

	/**
	 * @dataProvider createMockProvider
	 * @param string $description
	 * @param string $expression
	 */
	public function testCreateMock(string $description, string $expression): void
	{
		$this->assertTypes(
			__DIR__ . '/data/TestClass.php',
			$description,
			$expression,
			[new CreateMockDynamicReturnTypeExtension()]
		);
	}

}
