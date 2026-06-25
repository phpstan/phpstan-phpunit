<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Php\PhpVersion;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ClassAttributeRequiresPhpVersionRule>
 */
final class ClassAttributeRequiresPhpVersionRuleTest extends RuleTestCase
{

	private int $phpVersion = 80500;

	private int $phpunitMajorVersion;

	private int $phpunitMinorVersion;

	private bool $warnAboutIncompleteVersion = false;

	public function testWarnAboutIncompleteVersion(): void
	{
		$this->phpunitMajorVersion = 12;
		$this->phpunitMinorVersion = 5;
		$this->warnAboutIncompleteVersion = true;

		$this->analyse([__DIR__ . '/data/requires-php-version-on-class.php'], [
			[
				'Version requirement will always evaluate to false.',
				10,
			],
			[
				'Version requirement is incomplete.',
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
				'Version requirement is incomplete.',
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
				false,
				new PhpVersion($this->phpVersion),
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
