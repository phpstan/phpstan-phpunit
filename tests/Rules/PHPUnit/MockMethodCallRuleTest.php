<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\TestCase;
use function method_exists;
use const PHP_VERSION_ID;

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
			[
				'Trying to mock an undefined method doBadThing() on class MockMethodCall\Bar.',
				36,
			],
		];

		if (method_exists(TestCase::class, 'createMockForIntersectionOfInterfaces')) {
			$expectedErrors[] = [
				'Trying to mock an undefined method bazMethod() on class MockMethodCall\FooInterface&MockMethodCall\BarInterface.',
				49,
			];
			$expectedErrors[] = [
				'Trying to mock an undefined method bazMethod() on class MockMethodCall\FooInterface&MockMethodCall\BarInterface.',
				57,
			];
		}

		$this->analyse([__DIR__ . '/data/mock-method-call.php'], $expectedErrors);
	}

	public function testBug227(): void
	{
		if (PHP_VERSION_ID < 80000) {
			self::markTestSkipped('Test requires PHP 8.0.');
		}
		$this->analyse([__DIR__ . '/data/bug-227.php'], []);
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
