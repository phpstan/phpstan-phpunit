<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<AssertEqualsIsDiscouragedRule>
 */
class AssertEqualsIsDiscouragedRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new AssertEqualsIsDiscouragedRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assert-equals-is-discouraged.php'], [
			[
				'You should use assertSame instead of assertEquals, because both values are of the same type "string"',
				11,
			],
			[
				'You should use assertSame instead of assertEquals, because both values are of the same type "int"',
				12,
			],
			[
				'You should use assertSame instead of assertEquals, because both values are of the same type "bool"',
				13,
			],
			[
				'You should use assertSame instead of assertEquals, because both values are of the same type "float" and you are not using $delta argument',
				16,
			],
			[
				'You should use assertSame instead of assertEquals. Or it should have a comment above with explanation: // assertEquals because ... (There is a different comment)',
				19,
			],
			[
				'You should use assertSame instead of assertEquals. Or it should have a comment above with explanation: // assertEquals because ...',
				21,
			],
			[
				'You should use assertSame instead of assertEquals. Or it should have a comment above with explanation: // assertEquals because ... (There is a different comment)',
				24,
			],
			[
				'You should use assertSame instead of assertEquals. Or it should have a comment above with explanation: // assertEquals because ... (The comment is not directly above the assertEquals)',
				28,
			],
		]);
	}

}
