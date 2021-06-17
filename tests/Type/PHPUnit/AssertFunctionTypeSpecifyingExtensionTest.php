<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PHPStan\Testing\TypeInferenceTestCase;

class AssertFunctionTypeSpecifyingExtensionTest extends TypeInferenceTestCase
{

	/** @return mixed[] */
	public function dataFileAsserts(): iterable
	{
		yield from $this->gatherAssertTypes(__DIR__ . '/data/assert-function.php');
	}

	/**
	 * @dataProvider dataFileAsserts
	 * @param string $assertType
	 * @param string $file
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
