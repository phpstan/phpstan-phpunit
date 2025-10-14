<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Analyser\Scope;
use PHPStan\Parser\Parser;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
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
	public function getTestMethods(ClassReflection $classReflection, Scope $scope): array
	{
		$testMethods = [];
		foreach ($classReflection->getNativeReflection()->getMethods() as $reflectionMethod) {
			if (!$reflectionMethod->isPublic()) {
				continue;
			}

			if (str_starts_with(strtolower($reflectionMethod->getName()), 'test')) {
				$testMethods[] = $reflectionMethod;
				continue;
			}

			$docComment = $reflectionMethod->getDocComment();
			if ($docComment !== false) {
				$methodPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
					$scope->getFile(),
					$classReflection->getName(),
					$scope->isInTrait() ? $scope->getTraitReflection()->getName() : null,
					$reflectionMethod->getName(),
					$docComment,
				);

				if ($this->hasTestAnnotation($methodPhpDoc)) {
					$testMethods[] = $reflectionMethod;
					continue;
				}
			}

			// todo: detect tests with @test annotation

			// XXX
			//if (!$this->phpunit10OrNewer) {
			//	return;
			//}

			$testAttributes = $reflectionMethod->getAttributes('PHPUnit\Framework\Attributes\Test');
			if ($testAttributes === []) {
				continue;
			}

			$testMethods[] = $reflectionMethod;
		}

		return $testMethods;
	}

	private function hasTestAnnotation(?ResolvedPhpDocBlock $phpDoc): bool
	{
		if ($phpDoc === null) {
			return false;
		}

		$phpDocNodes = $phpDoc->getPhpDocNodes();

		foreach ($phpDocNodes as $docNode) {
			$tags = $docNode->getTagsByName('@test');
			if ($tags !== []) {
				return true;
			}
		}

		return false;
	}

}
