<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;

/**
 * @extends RuleTestCase<ClassMethodCoversExistsRule>
 */
class ClassMethodCoversExistsRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		$reflection = $this->createReflectionProvider();

		return new ClassMethodCoversExistsRule(
			new CoversHelper($reflection),
			self::getContainer()->getByType(FileTypeMapper::class)
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/method-coverage.php'], [
			[
				'@covers value \Not\A\Class::ignoreThis references an invalid method.',
				14,
			],
			[
				'@covers value \PHPUnit\Framework\TestCase::assertNotReal references an invalid method.',
				28,
			],
			[
				'@covers value \Not\A\Class::foo references an invalid method.',
				35,
			],
			[
				'@coversDefaultClass defined on class method testBadCoversDefault.',
				50,
			],
			[
				'@covers value \PHPUnit\Framework\TestCase::assertNotReal references an invalid method.',
				62,
			],
			[
				'Class already @covers \PHPUnit\Framework\TestCase so the method @covers is redundant.',
				85,
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
