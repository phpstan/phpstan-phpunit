<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<AssertSameWithCountRule>
 */
class AssertSameWithCountRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new AssertSameWithCountRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-same-count.php'], [
			[
				'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, count($variable)).',
				10,
			],
			[
				'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, count($variable)).',
				22,
			],
			[
				'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, $variable->count()).',
				30,
			],
			[
				'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, count($variable)).',
				40,
			],
			[
				'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, count($variable)).',
				45,
			],
		]);
	}

	public function testFix(): void
	{
		$this->fix(__DIR__ . '/data/assert-same-count-fixable.php', __DIR__ . '/data/assert-same-count-fixable.php.fixed');
	}

	public function testNamedArguments(): void
	{
		if (PHP_VERSION_ID < 80000) {
			self::markTestSkipped('Named arguments require PHP 8.0.');
		}

		$this->analyse([__DIR__ . '/data/assert-same-count-named-arguments.php'], []);
	}

	/**
	 * @return string[]
	 */
	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/../../../extension.neon',
		];
	}

}
