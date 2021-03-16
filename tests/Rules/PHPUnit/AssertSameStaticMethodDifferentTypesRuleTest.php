<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Comparison\ImpossibleCheckTypeHelper;
use PHPStan\Rules\Comparison\ImpossibleCheckTypeStaticMethodCallRule;
use PHPStan\Rules\Rule;
use PHPStan\Type\PHPUnit\Assert\AssertStaticMethodTypeSpecifyingExtension;

/**
 * @extends \PHPStan\Testing\RuleTestCase<ImpossibleCheckTypeStaticMethodCallRule>
 */
class AssertSameStaticMethodDifferentTypesRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new ImpossibleCheckTypeStaticMethodCallRule(new ImpossibleCheckTypeHelper($this->createBroker(), $this->getTypeSpecifier(), [], true), true, true);
	}

	/**
	 * @return \PHPStan\Type\StaticMethodTypeSpecifyingExtension[]
	 */
	protected function getStaticMethodTypeSpecifyingExtensions(): array
	{
		return [
			new AssertStaticMethodTypeSpecifyingExtension('PHPUnit\Framework\Assert'),
		];
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-same.php'], [
			[
				'Call to static method PHPUnit\Framework\Assert::assertSame() with \'1\' and 2 will always evaluate to false.',
				16,
			],
			[
				'Call to static method PHPUnit\Framework\Assert::assertSame() with \'1\' and 2 will always evaluate to false.',
				17,
			],
			[
				'Call to static method PHPUnit\Framework\Assert::assertSame() with \'1\' and 2 will always evaluate to false.',
				18,
			],
			[
				'Call to static method PHPUnit\Framework\Assert::assertSame() with 1 and 2 will always evaluate to false.',
				53,
			],
			[
				'Call to static method PHPUnit\Framework\Assert::assertSame() with 1 and 2 will always evaluate to false.',
				54,
			],
			[
				'Call to static method PHPUnit\Framework\Assert::assertSame() with 1 and 2 will always evaluate to false.',
				55,
			],
		]);
	}

}
