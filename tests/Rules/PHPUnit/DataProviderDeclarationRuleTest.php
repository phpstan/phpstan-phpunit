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
		$reflection = $this->createReflectionProvider();

		return new DataProviderDeclarationRule(
			new DataProviderHelper($reflection),
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
				14,
			],
			[
				'@dataProvider provideQux related method must be static.',
				14,
			],
			[
				'@dataProvider provideQuux related method must be public.',
				14,
			],
			[
				'@dataProvider provideNonExisting related method not found.',
				68,
			],
			[
				'@dataProvider NonExisting::provideNonExisting related class not found.',
				68,
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
