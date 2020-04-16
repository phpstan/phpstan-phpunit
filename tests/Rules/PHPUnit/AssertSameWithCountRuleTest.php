<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<AssertSameWithCountRule>
 */
class AssertSameWithCountRuleTest extends \PHPStan\Testing\RuleTestCase
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

}
