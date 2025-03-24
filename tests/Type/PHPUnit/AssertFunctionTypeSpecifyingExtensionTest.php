<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use function function_exists;

class AssertFunctionTypeSpecifyingExtensionTest extends TypeInferenceTestCase
{

	/** @return mixed[] */
	public static function dataFileAsserts(): iterable
	{
		if (function_exists('PHPUnit\\Framework\\assertInstanceOf')) {
			yield from self::gatherAssertTypes(__DIR__ . '/data/assert-function.php');
		}

		if (function_exists('PHPUnit\\Framework\\assertObjectHasProperty')) {
			yield from self::gatherAssertTypes(__DIR__ . '/data/assert-function-9.6.11.php');
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
