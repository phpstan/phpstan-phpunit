<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Php\ConfiguredPhpVersionRangeHelper;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;

/**
 * @extends RuleTestCase<AttributeRequiresPhpVersionRule>
 */
final class AttributeRequiresPhpVersionRuleTest extends RuleTestCase
{

	private ?int $phpunitMajorVersion;

	private ?int $phpunitMinorVersion;

	private bool $deprecationRulesInstalled = true;

	private bool $warnAboutIncompleteVersion = false;

	public function testRuleOnPHPUnitUnknown(): void
	{
		$this->phpunitMajorVersion = null;
		$this->phpunitMinorVersion = null;

		$this->analyse([__DIR__ . '/data/requires-php-version.php'], []);
	}

	public function testRuleOnPHPUnit115(): void
	{
		$this->phpunitMajorVersion = 11;
		$this->phpunitMinorVersion = 5;

		$this->analyse([__DIR__ . '/data/requires-php-version.php'], []);
	}

	public function testRuleOnPHPUnit123(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 3;

		$this->analyse([__DIR__ . '/data/requires-php-version.php'], []);
	}

	public function testRuleOnPHPUnit124DeprecationsOn(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 4;
		$this->deprecationRulesInstalled = true;

		$this->analyse([__DIR__ . '/data/requires-php-version.php'], [
			[
				'Version requirement without operator is deprecated.',
				12,
			],
		]);
	}

	public function testRuleOnPHPUnit124DeprecationsOff(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 4;
		$this->deprecationRulesInstalled = false;

		$this->analyse([__DIR__ . '/data/requires-php-version.php'], []);
	}

	public function testRuleOnPHPUnit13(): void
	{
		$this->phpunitMajorVersion = 13;
		$this->phpunitMinorVersion = 0;

		$this->analyse([__DIR__ . '/data/requires-php-version.php'], [
			[
				'Version requirement is missing operator.',
				12,
			],
		]);
	}

	public function testPhpVersionMismatch(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 4;
		$this->deprecationRulesInstalled = false;

		$this->analyse([__DIR__ . '/data/requires-php-version-mismatch.php'], [
			[
				// errors because https://github.com/sebastianbergmann/phpunit/issues/6451
				// the test assumes PHP_VERSION_ID 80500 and the constraint only has 2 digits
				'Version requirement will always evaluate to false.',
				12,
			],
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
				52,
			],
			[
				'Version requirement will always evaluate to false.',
				60,
			],
			[
				'Version requirement will always evaluate to false.',
				68,
			],
		]);
	}

	public function testInvalidPhpVersion(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 4;
		$this->deprecationRulesInstalled = false;

		$this->analyse([__DIR__ . '/data/requires-php-version-invalid.php'], [
			[
				'Version constraint abc is not supported.',
				12,
			],
		]);
	}

	public function testNoWarnAboutIncompleteVersionInOldPhpunit(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 0;
		$this->deprecationRulesInstalled = false;
		$this->warnAboutIncompleteVersion = true;

		$this->analyse([__DIR__ . '/data/requires-php-version.php'], []);
	}

	public function testWarnAboutIncompleteVersion(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 5;
		$this->deprecationRulesInstalled = false;
		$this->warnAboutIncompleteVersion = true;

		$this->analyse([__DIR__ . '/data/requires-php-version.php'], [
			[
				'Version requirement is incomplete.',
				12,
			],
			[
				'Version requirement is incomplete.',
				20,
			],
		]);
	}

	public function testWarnAboutIncompletePhpunitVersion(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 5;
		$this->deprecationRulesInstalled = false;
		$this->warnAboutIncompleteVersion = true;

		$this->analyse([__DIR__ . '/data/requires-phpunit-version.php'], [
			[
				'Version requirement is incomplete.',
				12,
			],
		]);
	}

	protected function getRule(): Rule
	{
		$phpunitVersion = new PHPUnitVersion($this->phpunitMajorVersion, $this->phpunitMinorVersion);

		return new AttributeRequiresPhpVersionRule(
			new TestMethodsHelper(
				self::getContainer()->getByType(FileTypeMapper::class),
				$phpunitVersion,
			),
			new AttributeVersionRequirementHelper(
				$phpunitVersion,
				self::getContainer()->getByType(ConfiguredPhpVersionRangeHelper::class), // @phpstan-ignore phpstanApi.classConstant
				$this->deprecationRulesInstalled,
				true,
				$this->warnAboutIncompleteVersion,
			),
		);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/AttributeRequiresPhpVersionRule.neon',
		];
	}

}
