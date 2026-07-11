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
		$tip = 'PHP version for analysis inferred from NEON config phpVersion or composer.json requirements. Invoke PHPStan with -vvv to get more details.';

		$this->analyse([__DIR__ . '/data/requires-php-version-mismatch.php'], [
			[
				'Version requirement < 7.0 does not match 8.2.0...8.4.0.',
				20,
				$tip,
			],
			[
				'Version requirement ^5.0 does not match 8.2.0...8.4.0.',
				28,
				$tip,
			],
			[
				'Version requirement ~5.0 does not match 8.2.0...8.4.0.',
				36,
				$tip,
			],
			[
				'Version requirement 5.* does not match 8.2.0...8.4.0.',
				44,
				$tip,
			],
			[
				'Version requirement 8.5.* does not match 8.2.0...8.4.0.',
				76,
				$tip,
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
				self::getContainer()->getByType(ConfiguredPhpVersionRangeHelper::class), // @phpstan-ignore phpstanApi.classConstant
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
