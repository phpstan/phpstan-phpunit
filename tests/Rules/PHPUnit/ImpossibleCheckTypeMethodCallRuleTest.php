<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Comparison\ImpossibleCheckTypeMethodCallRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ImpossibleCheckTypeMethodCallRule>
 */
class ImpossibleCheckTypeMethodCallRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return self::getContainer()->getByType(ImpossibleCheckTypeMethodCallRule::class);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/impossible-assert-method-call.php'], [
			[
				'Call to method PHPUnit\Framework\Assert::assertEmpty() with array{} will always evaluate to true.',
				14,
			],
			[
				'Call to method PHPUnit\Framework\Assert::assertEmpty() with array{1, 2, 3} will always evaluate to false.',
				15,
			],
		]);
	}

	public function testBug141(): void
	{
		$this->analyse([__DIR__ . '/data/bug-141.php'], [
			[
				"Call to method PHPUnit\Framework\Assert::assertEmpty() with non-empty-array<'0.6.0'|'1.0.0'|'1.0.x-dev'|'1.1.x-dev'|'9999999-dev'|'dev-feature-b', true> will always evaluate to false.",
				23,
				'Because the type is coming from a PHPDoc, you can turn off this check by setting <fg=cyan>treatPhpDocTypesAsCertain: false</> in your <fg=cyan>%configurationFile%</>.',
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
