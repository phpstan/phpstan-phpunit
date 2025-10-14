<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Parser\Parser;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\FileTypeMapper;
use ReflectionMethod;
use function str_starts_with;
use function strtolower;

final class TestMethodsHelper
{

	private ReflectionProvider $reflectionProvider;

	private FileTypeMapper $fileTypeMapper;

	private Parser $parser;

	public function __construct(
		ReflectionProvider $reflectionProvider,
		FileTypeMapper $fileTypeMapper,
		Parser $parser
	)
	{
		$this->reflectionProvider = $reflectionProvider;
		$this->fileTypeMapper = $fileTypeMapper;
		$this->parser = $parser;
	}

	/**
	 * @return array<ReflectionMethod>
	 */
	public function getTestMethods(ClassReflection $class): array
	{
		$testMethods = [];
		foreach ($class->getNativeReflection()->getMethods() as $reflectionMethod) {
			if (str_starts_with(strtolower($reflectionMethod->getName()), 'test')) {
				$testMethods[] = $reflectionMethod;
				continue;
			}

			// todo: detect tests with @test annotation

			$testAttributes = $reflectionMethod->getAttributes('PHPUnit\Framework\Attribute\Test');
			if ($testAttributes === []) {
				continue;
			}

			$testMethods[] = $reflectionMethod;
		}

		return $testMethods;
	}

}
