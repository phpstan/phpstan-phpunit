<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<NoMissingSpaceInAnnotationRule>
 */
class NoMissingSpaceInAnnotationRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new NoMissingSpaceInAnnotationRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/InvalidCoversAnnotation.php'], [
			[
				'Annotation "@backupGlobals" is invalid, "@backupGlobals" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@backupStaticAttributes" is invalid, "@backupStaticAttributes" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@covers\Dummy\Foo::assertSame" is invalid, "@covers" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@covers::assertSame" is invalid, "@covers" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@coversDefaultClass\Dummy\Foo" is invalid, "@coversDefaultClass" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@dataProvider" is invalid, "@dataProvider" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@depends" is invalid, "@depends" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@preserveGlobalState" is invalid, "@preserveGlobalState" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@requires" is invalid, "@requires" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@testDox" is invalid, "@testDox" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@testWith" is invalid, "@testWith" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@ticket" is invalid, "@ticket" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@uses" is invalid, "@uses" should be followed by a space and a value.',
				36,
			],
			[
				'Annotation "@backupGlobals" is invalid, "@backupGlobals" should be followed by a space and a value.',
				43,
			],
			[
				'Annotation "@backupStaticAttributes" is invalid, "@backupStaticAttributes" should be followed by a space and a value.',
				50,
			],
			[
				'Annotation "@covers\Dummy\Foo::assertSame" is invalid, "@covers" should be followed by a space and a value.',
				58,
			],
			[
				'Annotation "@covers::assertSame" is invalid, "@covers" should be followed by a space and a value.',
				58,
			],
			[
				'Annotation "@coversDefaultClass\Dummy\Foo" is invalid, "@coversDefaultClass" should be followed by a space and a value.',
				64,
			],
			[
				'Annotation "@dataProvider" is invalid, "@dataProvider" should be followed by a space and a value.',
				70,
			],
			[
				'Annotation "@depends" is invalid, "@depends" should be followed by a space and a value.',
				76,
			],
			[
				'Annotation "@preserveGlobalState" is invalid, "@preserveGlobalState" should be followed by a space and a value.',
				83,
			],
			[
				'Annotation "@requires" is invalid, "@requires" should be followed by a space and a value.',
				89,
			],
			[
				'Annotation "@testDox" is invalid, "@testDox" should be followed by a space and a value.',
				95,
			],
			[
				'Annotation "@testWith" is invalid, "@testWith" should be followed by a space and a value.',
				101,
			],
			[
				'Annotation "@ticket" is invalid, "@ticket" should be followed by a space and a value.',
				107,
			],
			[
				'Annotation "@uses" is invalid, "@uses" should be followed by a space and a value.',
				113,
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
