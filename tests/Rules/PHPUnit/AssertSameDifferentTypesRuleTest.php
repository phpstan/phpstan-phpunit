<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;

class AssertSameDifferentTypesRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new AssertSameDifferentTypesRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-same.php'], [
			[
				'Call to assertSame() with different types string and int(1) will always result in test failure.',
				10,
			],
			[
				'Call to assertSame() with different types string and stdClass will always result in test failure.',
				11,
			],
			[
				'Call to assertSame() with different types int(1) and string will always result in test failure.',
				12,
			],
			[
				'Call to assertSame() with different types string and int will always result in test failure.',
				13,
			],
			[
				'Call to assertSame() with different types array<int(0)|int(1), string> and array<int(0)|int(1), int(1)|int(2)> will always result in test failure.',
				14,
			],
			[
				'Call to assertSame() with different types string and int(2) will always result in test failure.',
				16,
			],
			[
				'Call to assertSame() with different types string and int(2) will always result in test failure.',
				17,
			],
			[
				'Call to assertSame() with different types string and int(2) will always result in test failure.',
				18,
			],
			[
				'Call to assertSame() with different types array<string> and array<int> will always result in test failure.',
				39,
			],
			[
				'Call to assertSame() with different types array<int(0), string> and array<int(0)|int(1), string> will always result in test failure.',
				45,
			],
			[
				'Call to assertSame() with different types string and string will always result in test failure.',
				47,
			],
			[
				'Call to assertSame() with different types array<int(0), string> and array<int(0)|int(1), int(1)|string> will always result in test failure.',
				51,
			],
			[
				'Call to assertSame() with different types array<int(0)|int(1)|int(2), float(3.000000)|int(2)|string> and array<int(0)|int(1), int(1)|string> will always result in test failure.',
				52,
			],
			[
				'Call to assertSame() with different types int(1) and int(2) will always result in test failure.',
				53,
			],
			[
				'Call to assertSame() with different types int(1) and int(2) will always result in test failure.',
				54,
			],
			[
				'Call to assertSame() with different types int(1) and int(2) will always result in test failure.',
				55,
			],
		]);
	}

}
