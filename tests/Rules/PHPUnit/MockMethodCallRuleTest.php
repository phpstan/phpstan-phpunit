<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<MockMethodCallRule>
 */
class MockMethodCallRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new MockMethodCallRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/mock-method-call.php'], [
			[
				'Trying to mock an undefined method doBadThing() on class MockMethodCall\Bar.',
				15,
			],
			[
				'Trying to mock an undefined method doBadThing() on class MockMethodCall\Bar.',
				20,
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
