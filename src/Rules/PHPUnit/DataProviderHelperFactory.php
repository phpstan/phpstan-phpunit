<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Parser\Parser;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;

class DataProviderHelperFactory
{

	private ReflectionProvider $reflectionProvider;

	private FileTypeMapper $fileTypeMapper;

	private Parser $parser;

	private PHPUnitVersionDetector $PHPUnitVersionDetector;

	public function __construct(
		ReflectionProvider $reflectionProvider,
		FileTypeMapper $fileTypeMapper,
		Parser $parser,
		PHPUnitVersionDetector $PHPUnitVersionDetector
	)
	{
		$this->reflectionProvider = $reflectionProvider;
		$this->fileTypeMapper = $fileTypeMapper;
		$this->parser = $parser;
		$this->PHPUnitVersionDetector = $PHPUnitVersionDetector;
	}

	public function create(): DataProviderHelper
	{
		return new DataProviderHelper($this->reflectionProvider, $this->fileTypeMapper, $this->parser, $this->PHPUnitVersionDetector->isPHPUnit10OrNewer());
	}

}
