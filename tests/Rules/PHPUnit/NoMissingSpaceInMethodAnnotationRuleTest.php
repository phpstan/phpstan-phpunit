<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<NoMissingSpaceInMethodAnnotationRule>
 */
class NoMissingSpaceInMethodAnnotationRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new NoMissingSpaceInMethodAnnotationRule(new AnnotationHelper());
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/InvalidMethodCoversAnnotation.php'], [
			[
				'Annotation "@backupGlobals" is invalid, "@backupGlobals" should be followed by a space and a value.',
				12,
			],
			[
				'Annotation "@backupStaticAttributes" is invalid, "@backupStaticAttributes" should be followed by a space and a value.',
				19,
			],
			[
				'Annotation "@covers\Dummy\Foo::assertSame" is invalid, "@covers" should be followed by a space and a value.',
				27,
			],
			[
				'Annotation "@covers::assertSame" is invalid, "@covers" should be followed by a space and a value.',
				27,
			],
			[
				'Annotation "@coversDefaultClass\Dummy\Foo" is invalid, "@coversDefaultClass" should be followed by a space and a value.',
				33,
			],
			[
				'Annotation "@dataProvider" is invalid, "@dataProvider" should be followed by a space and a value.',
				39,
			],
			[
				'Annotation "@depends" is invalid, "@depends" should be followed by a space and a value.',
				45,
			],
			[
				'Annotation "@preserveGlobalState" is invalid, "@preserveGlobalState" should be followed by a space and a value.',
				52,
			],
			[
				'Annotation "@requires" is invalid, "@requires" should be followed by a space and a value.',
				58,
			],
			[
				'Annotation "@testDox" is invalid, "@testDox" should be followed by a space and a value.',
				64,
			],
			[
				'Annotation "@testWith" is invalid, "@testWith" should be followed by a space and a value.',
				70,
			],
			[
				'Annotation "@ticket" is invalid, "@ticket" should be followed by a space and a value.',
				76,
			],
			[
				'Annotation "@uses" is invalid, "@uses" should be followed by a space and a value.',
				82,
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
