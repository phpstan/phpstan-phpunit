<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Php\ConfiguredPhpVersionRangeHelper;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;

/**
 * @extends RuleTestCase<AttributeRequiresPhpVersionRule>
 */
final class AttributeRequiresPhpVersionRangeRuleTest extends RuleTestCase
{

	public function testPhpVersionMismatch(): void
	{
		$this->analyse([__DIR__ . '/data/requires-php-version-mismatch.php'], [
			[
				'Version requirement will always evaluate to false.',
				20,
			],
			[
				'Version requirement will always evaluate to false.',
				28,
			],
			[
				'Version requirement will always evaluate to false.',
				36,
			],
			[
				'Version requirement will always evaluate to false.',
				44,
			],
			[
				'Version requirement will always evaluate to false.',
				76,
			],
		]);
	}

	protected function getRule(): Rule
	{
		$phpunitVersion = new PHPUnitVersion(null, null);

		return new AttributeRequiresPhpVersionRule(
			new TestMethodsHelper(
				self::getContainer()->getByType(FileTypeMapper::class),
				$phpunitVersion,
			),
			new AttributeVersionRequirementHelper(
				$phpunitVersion,
				self::getContainer()->getByType(ConfiguredPhpVersionRangeHelper::class),
				false,
				true,
			),
		);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/AttributeRequiresPhpVersionRangeRule.neon',
		];
	}

}
