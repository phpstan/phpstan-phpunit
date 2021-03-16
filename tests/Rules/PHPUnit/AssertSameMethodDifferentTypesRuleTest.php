<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Comparison\ImpossibleCheckTypeHelper;
use PHPStan\Rules\Comparison\ImpossibleCheckTypeMethodCallRule;
use PHPStan\Rules\Rule;
use PHPStan\Type\PHPUnit\Assert\AssertMethodTypeSpecifyingExtension;

/**
 * @extends \PHPStan\Testing\RuleTestCase<ImpossibleCheckTypeMethodCallRule>
 */
class AssertSameMethodDifferentTypesRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new ImpossibleCheckTypeMethodCallRule(new ImpossibleCheckTypeHelper($this->createBroker(), $this->getTypeSpecifier(), [], true), true, true);
	}

	/**
	 * @return \PHPStan\Type\MethodTypeSpecifyingExtension[]
	 */
	protected function getMethodTypeSpecifyingExtensions(): array
	{
		return [
			new AssertMethodTypeSpecifyingExtension('PHPUnit\Framework\TestCase'),
		];
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-same.php'], [
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with \'1\' and 1 will always evaluate to false.',
				10,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with \'1\' and stdClass will always evaluate to false.',
				11,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with 1 and string will always evaluate to false.',
				12,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with \'1\' and int will always evaluate to false.',
				13,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with array(\'a\', \'b\') and array(1, 2) will always evaluate to false.',
				14,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with array<string> and array<int> will always evaluate to false.',
				39,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with 1 and 1 will always evaluate to true.',
				44,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with \'1\' and \'1\' will always evaluate to true.',
				46,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with \'1\' and \'2\' will always evaluate to false.',
				47,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertSame() with array(\'a\', 2, 3.0) and array(\'a\', 1) will always evaluate to false.',
				52,
			],
		]);
	}

}
