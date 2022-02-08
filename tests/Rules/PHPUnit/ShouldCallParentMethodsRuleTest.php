<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ShouldCallParentMethodsRule>
 */
class ShouldCallParentMethodsRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new ShouldCallParentMethodsRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/missing-parent-method-calls.php'], [
			[
				'Missing call to parent::setUp() method.',
				32,
			],
			[
				'Missing call to parent::setUp() method.',
				55,
			],
			[
				'Missing call to parent::tearDown() method.',
				63,
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
