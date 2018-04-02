<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Comparison\ImpossibleCheckTypeMethodCallRule;
use PHPStan\Rules\Rule;
use PHPStan\Type\PHPUnit\Assert\AssertMethodTypeSpecifyingExtension;

class AssertSameMethodDifferentTypesRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new ImpossibleCheckTypeMethodCallRule(true);
	}

	/**
	 * @return \PHPStan\Type\MethodTypeSpecifyingExtension[]
	 */
	protected function getMethodTypeSpecifyingExtensions(): array
	{
		return [
			new AssertMethodTypeSpecifyingExtension(),
		];
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-same.php'], [
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				10,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				11,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				12,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				13,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				14,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				39,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to true.',
				44,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				45,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to true.',
				46,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				47,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to true.',
				48,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				51,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() will always evaluate to false.',
				52,
			],
		]);
	}

}
