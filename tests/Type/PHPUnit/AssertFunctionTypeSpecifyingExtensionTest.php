<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PHPStan\Testing\TypeInferenceTestCase;
use function function_exists;

class AssertFunctionTypeSpecifyingExtensionTest extends TypeInferenceTestCase
{

	/** @return mixed[] */
	public function dataFileAsserts(): iterable
	{
		if (!function_exists('PHPUnit\\Framework\\assertInstanceOf')) {
			return [];
		}

		yield from $this->gatherAssertTypes(__DIR__ . '/data/assert-function.php');
	}

	/**
	 * @dataProvider dataFileAsserts
	 * @param mixed ...$args
	 */
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
