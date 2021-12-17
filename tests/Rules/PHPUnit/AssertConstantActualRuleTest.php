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
				11,
			],
			[
				'The value of `$actual` should not be a constant',
				17,
			],
			[
				'The value of `$actual` should not be a constant',
				29,
			],
			[
				'The value of `$actual` should not be a constant',
				35,
			],
			[
				'The value of `$actual` should not be a constant',
				47,
			],
			[
				'The value of `$actual` should not be a constant',
				53,
			],
			[
				'The value of `$actual` should not be a constant',
				65,
			],
			[
				'The value of `$actual` should not be a constant',
				71,
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
