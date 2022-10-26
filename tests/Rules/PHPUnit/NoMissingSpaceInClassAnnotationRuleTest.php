<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<NoMissingSpaceInClassAnnotationRule>
 */
class NoMissingSpaceInClassAnnotationRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new NoMissingSpaceInClassAnnotationRule(new AnnotationHelper());
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/InvalidClassCoversAnnotation.php'], [
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
