<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use function interface_exists;

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
		$expectedErrors = [
			[
				'Trying to mock an undefined method doBadThing() on class MockMethodCall\Bar.',
				15,
			],
			[
				'Trying to mock an undefined method doBadThing() on class MockMethodCall\Bar.',
				20,
			],
		];

		if (interface_exists('PHPUnit\Framework\MockObject\Builder\InvocationStubber')) {
			$expectedErrors[] = [
				'Trying to mock an undefined method doBadThing() on class MockMethodCall\Bar.',
				36,
			];
		}

		$this->analyse([__DIR__ . '/data/mock-method-call.php'], $expectedErrors);
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
