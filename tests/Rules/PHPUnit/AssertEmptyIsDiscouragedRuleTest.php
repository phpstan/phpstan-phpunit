<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<AssertEmptyIsDiscouragedRule>
 */
final class AssertEmptyIsDiscouragedRuleTest extends RuleTestCase
{

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-empty-is-discouraged.php'], [
			['assertEmpty() is not allowed. Use more strict assertion.', 15],
			['assertNotEmpty() is not allowed. Use more strict assertion.', 16],
			['assertEmpty() is not allowed. Use more strict assertion.', 17],
			['assertNotEmpty() is not allowed. Use more strict assertion.', 18],
			['assertEmpty() is not allowed. Use more strict assertion.', 19],
			['assertNotEmpty() is not allowed. Use more strict assertion.', 20],
		]);
	}

	protected function getRule(): Rule
	{
		return new AssertEmptyIsDiscouragedRule();
	}

}
