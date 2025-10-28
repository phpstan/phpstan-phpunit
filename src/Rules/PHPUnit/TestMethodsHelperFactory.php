<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Type\FileTypeMapper;

class TestMethodsHelperFactory
{

	private FileTypeMapper $fileTypeMapper;

	private PHPUnitVersionDetector $PHPUnitVersionDetector;

	public function __construct(
		FileTypeMapper $fileTypeMapper,
		PHPUnitVersionDetector $PHPUnitVersionDetector
	)
	{
		$this->fileTypeMapper = $fileTypeMapper;
		$this->PHPUnitVersionDetector = $PHPUnitVersionDetector;
	}

	public function create(): TestMethodsHelper
	{
		return new TestMethodsHelper($this->fileTypeMapper, $this->PHPUnitVersionDetector->getPHPUnitVersion());
	}

}
