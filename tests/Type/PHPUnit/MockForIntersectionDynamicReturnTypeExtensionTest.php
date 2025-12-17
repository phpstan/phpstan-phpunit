<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function method_exists;

class MockForIntersectionDynamicReturnTypeExtensionTest extends TypeInferenceTestCase
{

	/** @return mixed[] */
	public static function dataFileAsserts(): iterable
	{
		if (method_exists(TestCase::class, 'createMockForIntersectionOfInterfaces')) { // @phpstan-ignore-line function.alreadyNarrowedType
			yield from self::gatherAssertTypes(__DIR__ . '/data/mock-for-intersection.php');
		}

		return [];
	}

	/**
	 * @dataProvider dataFileAsserts
	 * @param mixed ...$args
	 */
	#[DataProvider('dataFileAsserts')]
	public function testFileAsserts(
		string $assertType,
		string $file,
		...$args
	): void
	{
		$this->assertFileAsserts($assertType, $file, ...$args);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [__DIR__ . '/../../../extension.neon'];
	}

}
