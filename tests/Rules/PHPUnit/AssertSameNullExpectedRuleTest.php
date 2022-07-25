<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<AssertSameNullExpectedRule>
 */
class AssertSameNullExpectedRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new AssertSameNullExpectedRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-same-null-expected.php'], [
			[
				'You should use assertNull() instead of assertSame(null, $actual).',
				10,
			],
			[
				'You should use assertNull() instead of assertSame(null, $actual).',
				24,
			],
			[
				'You should use assertNull() instead of assertSame(null, $actual).',
				60,
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
