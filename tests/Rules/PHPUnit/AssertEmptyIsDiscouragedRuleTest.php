<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<AssertEmptyIsDiscouragedRule>
 */
final class AssertEmptyIsDiscouragedRuleTest extends RuleTestCase
{

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-empty-is-discouraged.php'], [
			['assertEmpty() is not allowed. Use more strict assertion.', 13],
			['assertNotEmpty() is not allowed. Use more strict assertion.', 14],
			['assertEmpty() is not allowed. Use more strict assertion.', 15],
			['assertNotEmpty() is not allowed. Use more strict assertion.', 16],
		]);
	}

	public function testFix(): void
	{
		$this->fix(__DIR__ . '/data/assert-empty-is-discouraged-fixable.php', __DIR__ . '/data/assert-empty-is-discouraged-fixable.php.fixed');
	}

	public function testNativeUnionTypeIsNotFixed(): void
	{
		if (PHP_VERSION_ID < 80000) {
			$this->markTestSkipped('Native union types require PHP 8.0.');
		}

		$this->fix(__DIR__ . '/data/assert-empty-is-discouraged-native-union.php', __DIR__ . '/data/assert-empty-is-discouraged-native-union.php.fixed');
	}

	protected function getRule(): Rule
	{
		return new AssertEmptyIsDiscouragedRule();
	}

}
