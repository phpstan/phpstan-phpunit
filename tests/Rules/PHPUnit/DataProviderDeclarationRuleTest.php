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
			new DataProviderHelper($reflection, self::getContainer()->getByType(FileTypeMapper::class),true),
			true,
			true
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/data-provider-declaration.php'], [
			[
				'@dataProvider providebaz related method is used with incorrect case: provideBaz.',
				16,
			],
			[
				'@dataProvider provideQux related method must be static in PHPUnit 10 and newer.',
				16,
			],
			[
				'@dataProvider provideQuux related method must be public.',
				16,
			],
			[
				'@dataProvider provideNonExisting related method not found.',
				70,
			],
			[
				'@dataProvider NonExisting::provideNonExisting related class not found.',
				70,
			],
			[
				'@dataProvider provideNonExisting related method not found.',
				85,
			],
			[
				'@dataProvider provideNonExisting2 related method not found.',
				86,
			],
			[
				'@dataProvider ExampleTestCase\\BarTestCase::providetootherclass related method is used with incorrect case: provideToOtherClass.',
				87,
			],
			[
				'@dataProvider ExampleTestCase\\BarTestCase::providetootherclass related method is used with incorrect case: provideToOtherClass.',
				88,
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
