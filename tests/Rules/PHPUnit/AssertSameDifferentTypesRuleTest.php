<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;

class AssertSameDifferentTypesRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new AssertSameDifferentTypesRule();
	}

	public function testRule()
	{
		$this->analyse([__DIR__ . '/data/assert-same.php'], [
			[
				'Call to assertSame() with different types string and int will always result in test failure.',
				10,
			],
			[
				'Call to assertSame() with different types string and stdClass will always result in test failure.',
				11,
			],
			[
				'Call to assertSame() with different types int and string will always result in test failure.',
				12,
			],
			[
				'Call to assertSame() with different types string and int will always result in test failure.',
				13,
			],
			[
				'Call to assertSame() with different types array<int, string> and array<int, int> will always result in test failure.',
				14,
			],
			[
				'Call to assertSame() with different types array<string> and array<int> will always result in test failure.',
				35,
			],
		]);
	}

}
