<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

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
		]);
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
