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
		return new AssertSameWithCountRule(true);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-same-count.php'], [
			[
				'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, count($variable)).',
				10,
			],
			[
				'You should use assertSameSize($expected, $variable) instead of assertSame(count($expected), count($variable)).',
				15,
			],
			[
				'You should use assertSameSize($expected, $variable) instead of assertSame($expected->count(), count($variable)).',
				23,
			],
			[
				'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, count($variable)).',
				35,
			],
			[
				'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, $variable->count()).',
				43,
			],
			[
				'You should use assertSameSize($expected, $variable) instead of assertSame(count($expected), $variable->count()).',
				51,
			],
			[
				'You should use assertSameSize($expected, $variable) instead of assertSame($expected->count(), $variable->count()).',
				61,
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
