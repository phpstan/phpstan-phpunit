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
		$message = 'The value of `$actual` should not be a constant';
		$this->analyse([__DIR__ . '/data/assert-constant-actual.php'], [
			[
				$message,
				11,
			],
			[
				$message,
				17,
			],
			[
				$message,
				29,
			],
			[
				$message,
				35,
			],
			[
				$message,
				47,
			],
			[
				$message,
				53,
			],
			[
				$message,
				65,
			],
			[
				$message,
				71,
			],
			[
				$message,
				98,
			],
			[
				$message,
				99,
			],
			[
				$message,
				100,
			],
			[
				$message,
				101,
			],
			[
				$message,
				102,
			],
			[
				$message,
				103,
			],
			[
				$message,
				104,
			],
			[
				$message,
				105,
			],
			[
				$message,
				106,
			],
			[
				$message,
				107,
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
