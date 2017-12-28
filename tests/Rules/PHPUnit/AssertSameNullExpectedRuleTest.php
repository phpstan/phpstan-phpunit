<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;

class AssertSameNullExpectedRuleTest extends \PHPStan\Testing\RuleTestCase
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
				13,
			],
		]);
	}

}
