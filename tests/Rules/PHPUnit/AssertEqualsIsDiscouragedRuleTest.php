<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<AssertEqualsIsDiscouragedRule>
 */
final class AssertEqualsIsDiscouragedRuleTest extends RuleTestCase
{

	private const ERROR_MESSAGE_EQUALS = 'You should use assertSame() instead of assertEquals(), because both values are scalars of the same type';
	private const ERROR_MESSAGE_NOT_EQUALS = 'You should use assertNotSame() instead of assertNotEquals(), because both values are scalars of the same type';

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-equals-is-discouraged.php'], [
			[self::ERROR_MESSAGE_EQUALS, 19],
			[self::ERROR_MESSAGE_EQUALS, 22],
			[self::ERROR_MESSAGE_EQUALS, 23],
			[self::ERROR_MESSAGE_EQUALS, 24],
			[self::ERROR_MESSAGE_EQUALS, 25],
			[self::ERROR_MESSAGE_EQUALS, 26],
			[self::ERROR_MESSAGE_EQUALS, 27],
			[self::ERROR_MESSAGE_EQUALS, 28],
			[self::ERROR_MESSAGE_EQUALS, 29],
			[self::ERROR_MESSAGE_EQUALS, 30],
			[self::ERROR_MESSAGE_EQUALS, 32],
			[self::ERROR_MESSAGE_NOT_EQUALS, 37],
			[self::ERROR_MESSAGE_NOT_EQUALS, 38],
			[self::ERROR_MESSAGE_NOT_EQUALS, 39],
		]);
	}

	protected function getRule(): Rule
	{
		return new AssertEqualsIsDiscouragedRule();
	}

}
