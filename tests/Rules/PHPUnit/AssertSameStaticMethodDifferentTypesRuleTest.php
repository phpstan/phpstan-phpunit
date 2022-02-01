<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Comparison\ImpossibleCheckTypeStaticMethodCallRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ImpossibleCheckTypeStaticMethodCallRule>
 */
class AssertSameStaticMethodDifferentTypesRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return self::getContainer()->getByType(ImpossibleCheckTypeStaticMethodCallRule::class);
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

	/**
	 * @return string[]
	 */
	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/../../../extension.neon',
			__DIR__ . '/../../../vendor/phpstan/phpstan-strict-rules/rules.neon',
		];
	}

}
