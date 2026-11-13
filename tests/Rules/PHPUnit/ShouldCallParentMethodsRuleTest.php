<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use const PHP_VERSION_ID;

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
			[
				'Missing call to parent::setUp() method.',
				140,
			],
			[
				'Missing call to parent::tearDown() method.',
				147,
			],
			[
				'Missing call to parent::setUp() method.',
				155,
			],
		]);
	}

	public function testFirstClassCallable(): void
	{
		if (PHP_VERSION_ID < 80100) {
			self::markTestSkipped('First-class callable syntax requires PHP 8.1.');
		}

		$this->analyse([__DIR__ . '/data/missing-parent-method-calls-first-class-callable.php'], [
			[
				'Missing call to parent::setUp() method.',
				20,
			],
			[
				'Missing call to parent::tearDown() method.',
				25,
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
