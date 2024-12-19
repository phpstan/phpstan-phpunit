<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<AssertEqualsIsDiscouragedRule>
 */
final class AssertEqualsIsDiscouragedRuleTest extends RuleTestCase
{

	private const ERROR_MESSAGE = 'You should use assertSame() instead of assertEquals(), because both values are scalars of the same type';

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-equals-is-discouraged.php'], [
			[self::ERROR_MESSAGE, 19],
			[self::ERROR_MESSAGE, 22],
			[self::ERROR_MESSAGE, 23],
			[self::ERROR_MESSAGE, 24],
			[self::ERROR_MESSAGE, 25],
			[self::ERROR_MESSAGE, 26],
			[self::ERROR_MESSAGE, 27],
			[self::ERROR_MESSAGE, 28],
			[self::ERROR_MESSAGE, 29],
			[self::ERROR_MESSAGE, 30],
			[self::ERROR_MESSAGE, 32],
			[self::ERROR_MESSAGE, 37],
			[self::ERROR_MESSAGE, 38],
			[self::ERROR_MESSAGE, 39],
		]);
	}

	protected function getRule(): Rule
	{
		return new AssertEqualsIsDiscouragedRule();
	}

}
