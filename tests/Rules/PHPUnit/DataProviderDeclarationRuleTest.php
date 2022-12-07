<?php declare(strict_types=1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;

/**
 * @extends RuleTestCase<DataProviderDeclarationRule>
 */
class DataProviderDeclarationRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new DataProviderDeclarationRule(
			new DataProviderHelper(),
			self::getContainer()->getByType(FileTypeMapper::class),
			true,
			true
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/data-provider-declaration.php'], [
			[
				'@dataProvider providebaz related method is used with incorrect case: provideBaz.',
				13,
			],
			[
				'@dataProvider provideQux related method must be static.',
				13,
			],
			[
				'@dataProvider provideQuux related method must be public.',
				13,
			],
			[
				'@dataProvider provideNonExisting related method not found.',
				66,
			],
			[
				'@dataProvider provideMultiple returns a different number of values the test method expects.',
				79,
			],
			[
				'@dataProvider provideArray returns iterable<int, array<string>> which is not compatible with the test method parameters.',
				101,
			],
			[
				'@dataProvider provideIterator returns Iterator<mixed, array<int, string>> which is not compatible with the test method parameters.',
				101,
			],
			[
				'@dataProvider provideMultiple returns a different number of values the test method expects.',
				101,
			],
			[
				'@dataProvider provideMultiple returns Iterator<mixed, array{string, int}> which is not compatible with the test method parameters.',
				116,
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
