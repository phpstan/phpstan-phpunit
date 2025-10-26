<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Analyser\Scope;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\FileTypeMapper;
use ReflectionMethod;
use function str_starts_with;
use function strtolower;

final class TestMethodsHelper
{

	private FileTypeMapper $fileTypeMapper;

	private bool $phpunit10OrNewer;

	public function __construct(
		FileTypeMapper $fileTypeMapper,
		bool $phpunit10OrNewer
	)
	{
		$this->fileTypeMapper = $fileTypeMapper;
		$this->phpunit10OrNewer = $phpunit10OrNewer;
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

			if (!$this->phpunit10OrNewer) {
				continue;
			}

			$testAttributes = $reflectionMethod->getAttributes('PHPUnit\Framework\Attributes\Test'); // @phpstan-ignore argument.type
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
