<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ClassNamingRule>
 */
final class ClassNamingRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new ClassNamingRule(self::getContainer()->getByType(ReflectionProvider::class));
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/class-naming.php'], [
			['Concrete test class, \'ExampleTestCase\\IncorrectlyNamedTst\', should be named ending in \'Test\'.', 5],
			['Abstract test case class, \'ExampleTestCase\\IncorrectlyNamedTestCse\', should be named ending in \'TestCase\'.', 10],
			['Concrete test class, \'ExampleTestCase\\NotFinalTest\', should be declared final.', 15],
			['Concrete test class, \'ExampleTestCase\\NotFinalOrNamedCorrectly\', should be named ending in \'Test\'.', 20],
			['Concrete test class, \'ExampleTestCase\\NotFinalOrNamedCorrectly\', should be declared final.', 20],
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
