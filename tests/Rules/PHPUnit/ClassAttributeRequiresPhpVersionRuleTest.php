<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Php\ConfiguredPhpVersionRangeHelper;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ClassAttributeRequiresPhpVersionRule>
 */
final class ClassAttributeRequiresPhpVersionRuleTest extends RuleTestCase
{

	private int $phpunitMajorVersion;

	private int $phpunitMinorVersion;

	private bool $warnAboutIncompleteVersion = false;

	public function testWarnAboutIncompleteVersion(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 5;
		$this->warnAboutIncompleteVersion = true;

		$tip = 'PHP version for analysis inferred from NEON config phpVersion or composer.json requirements. Invoke PHPStan with -vvv to get more details.';

		$this->analyse([__DIR__ . '/data/requires-php-version-on-class.php'], [
			[
				'Version requirement < 7.0 does not match 8.5.0...8.5.99.',
				10,
				$tip,
			],
			[
				'Version requirement < 7.0 is incomplete. Expect a version composed of major, minor and patch.',
				10,
			],
		]);
	}

	public function testWarnAboutIncompletePhpunitVersion(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 5;
		$this->warnAboutIncompleteVersion = true;

		$this->analyse([__DIR__ . '/data/requires-phpunit-version.php'], [
			[
				'Version requirement >=11.0 is incomplete. Expect a version composed of major, minor and patch.',
				18,
			],
		]);
	}

	protected function getRule(): Rule
	{
		$phpunitVersion = new PHPUnitVersion($this->phpunitMajorVersion, $this->phpunitMinorVersion);

		return new ClassAttributeRequiresPhpVersionRule(
			new AttributeVersionRequirementHelper(
				$phpunitVersion,
				self::getContainer()->getByType(ConfiguredPhpVersionRangeHelper::class), // @phpstan-ignore phpstanApi.classConstant
				false,
				true,
				$this->warnAboutIncompleteVersion,
			),
		);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/ClassAttributeRequiresPhpVersionRule.neon',
		];
	}

}
