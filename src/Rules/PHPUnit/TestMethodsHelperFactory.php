<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Parser\Parser;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;
use PHPUnit\Framework\TestCase;
use function dirname;
use function explode;
use function file_get_contents;
use function is_file;
use function json_decode;

class TestMethodsHelperFactory
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

	public function create(): TestMethodsHelper
	{
		return new TestMethodsHelper($this->reflectionProvider, $this->fileTypeMapper, $this->parser, $this->PHPUnitVersionDetector->isPHPUnit10OrNewer());
	}

}
