<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;

class AssertSameWithCountRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new AssertSameWithCountRule();
	}

	public function testRule()
	{
		$this->analyse([__DIR__ . '/data/assert-same-count.php'], [
			[
				'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, count($variable)).',
				10,
			],
		]);
	}

}
