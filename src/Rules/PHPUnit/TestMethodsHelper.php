<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Analyser\Scope;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\FileTypeMapper;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use function str_starts_with;
use function strtolower;

final class TestMethodsHelper
{

	private FileTypeMapper $fileTypeMapper;

	private PHPUnitVersion $PHPUnitVersion;

	public function __construct(
		FileTypeMapper $fileTypeMapper,
		PHPUnitVersion $PHPUnitVersion
	)
	{
		$this->fileTypeMapper = $fileTypeMapper;
		$this->PHPUnitVersion = $PHPUnitVersion;
	}

	public function getTestMethodReflection(ClassReflection $classReflection, MethodReflection $methodReflection, Scope $scope): ?ReflectionMethod
	{
		foreach ($this->getTestMethods($classReflection, $scope) as $testMethod) {
			if ($testMethod->getName() === $methodReflection->getName()) {
				return $testMethod;
			}
		}

		return null;
	}

	/**
	 * @return array<ReflectionMethod>
	 */
	public function getTestMethods(ClassReflection $classReflection, Scope $scope): array
	{
		if (!$classReflection->is(TestCase::class)) {
			return [];
		}

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

			if ($this->PHPUnitVersion->supportsTestAttribute()->no()) {
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
