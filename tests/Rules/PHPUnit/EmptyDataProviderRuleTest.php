<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<EmptyDataProviderRule>
 */
class EmptyDataProviderRuleTest extends RuleTestCase
{

	private ?int $phpunitVersion;

	protected function getRule(): Rule
	{
		$phpunitVersion = new PHPUnitVersion($this->phpunitVersion, 0);

		return new EmptyDataProviderRule(
			new TestMethodsHelper(
				self::getContainer()->getByType(FileTypeMapper::class),
				$phpunitVersion,
			),
			new DataProviderHelper(
				$this->createReflectionProvider(),
				self::getContainer()->getByType(FileTypeMapper::class),
				self::getContainer()->getService('defaultAnalysisParser'),
				$phpunitVersion,
			),
			$phpunitVersion,
		);
	}

	/**
	 * @dataProvider provideVersions
	 */
	#[DataProvider('provideVersions')]
	public function testRule(?int $version): void
	{
		if (PHP_VERSION_ID < 80000) {
			self::markTestSkipped('Test requires PHP 8.0 for attributes.');
		}

		$this->phpunitVersion = $version;

		$errors = [];

		// PHPUnit 9 skips an empty data provider instead of erroring.
		if ($version === null || $version >= 10) {
			$errors[] = [
				'Data provider method provideData() provides no data sets, which is an error in PHPUnit 10 and newer.',
				21,
			];
			$errors[] = [
				'Data provider method provideData() provides no data sets, which is an error in PHPUnit 10 and newer.',
				109,
			];
			$errors[] = [
				'Data provider method provideData() provides no data sets, which is an error in PHPUnit 10 and newer.',
				158,
			];
		}

		// DataProviderHelper only reads the attribute when the detected major version supports it.
		if ($version !== null && $version >= 10) {
			$errors[] = [
				'Data provider method provideData() provides no data sets, which is an error in PHPUnit 10 and newer.',
				173,
			];
		}

		$this->analyse([__DIR__ . '/data/empty-data-provider.php'], $errors);
	}

	/**
	 * @return iterable<array{?int}>
	 */
	public static function provideVersions(): iterable
	{
		yield [null];
		yield [9];
		yield [10];
		yield [11];
		yield [12];
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [__DIR__ . '/../../../extension.neon'];
	}

}
