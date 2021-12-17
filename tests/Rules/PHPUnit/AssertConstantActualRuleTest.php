<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<AssertConstantActualRule>
 */
class AssertConstantActualRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new AssertConstantActualRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-constant-actual.php'], [
			[
				'The value of `$actual` should not be a constant',
				10,
			],
			[
				'The value of `$actual` should not be a constant',
				16,
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
