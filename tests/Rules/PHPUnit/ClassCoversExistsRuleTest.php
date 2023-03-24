<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ClassCoversExistsRule>
 */
class ClassCoversExistsRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		$reflection = $this->createReflectionProvider();

		return new ClassCoversExistsRule(
			new CoversHelper($reflection),
			$reflection
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/class-coverage.php'], [
			[
				'@coversDefaultClass references an invalid class \Not\A\Class.',
				8,
			],
			[
				'@coversDefaultClass is defined multiple times.',
				23,
			],
			[
				'@covers value \Not\A\Class references an invalid class or function.',
				31,
			],
			[
				'@covers value does not specify anything.',
				43,
			],
			[
				'@covers value NotFullyQualified references an invalid class or function.',
				50,
				'The @covers annotation requires a fully qualified name.',
			],
			[
				'@covers value \DateTimeInterface references an interface.',
				64,
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
