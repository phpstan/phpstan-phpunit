<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassMethod;
use PHPStan\Parser\Parser;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\FileTypeMapper;
use ReflectionMethod;
use function array_merge;
use function explode;
use function sprintf;
use function strpos;

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
		foreach($class->getNativeReflection()->getMethods() as $reflectionMethod) {
			if (str_starts_with(strtolower($reflectionMethod->getName()), 'test')) {
				$testMethods[] = $reflectionMethod;
				continue;
			}

			// todo: detect tests with @test annotation

			$testAttributes = $reflectionMethod->getAttributes('PHPUnit\Framework\Attribute\Test');
			if ($testAttributes !== []) {
				$testMethods[] = $reflectionMethod;
			}
		}

		return $testMethods;
	}

	/**
	 * @return iterable<array{string}>
	 */
	public function getDataProviderMethods(
		Scope                            $scope,
		ReflectionMethod $node,
		ClassReflection                  $classReflection
	): iterable
	{
		/*
		$docComment = $node->getDocComment();
		if ($docComment !== null) {
			$methodPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
				$scope->getFile(),
				$classReflection->getName(),
				$scope->isInTrait() ? $scope->getTraitReflection()->getName() : null,
				$node->name->toString(),
				$docComment->getText(),
			);
			foreach ($this->getDataProviderAnnotations($methodPhpDoc) as $annotation) {
				$dataProviderValue = $this->getDataProviderAnnotationValue($annotation);
				if ($dataProviderValue === null) {
					// Missing value is already handled in NoMissingSpaceInMethodAnnotationRule
					continue;
				}

				$dataProviderMethod = $this->parseDataProviderAnnotationValue($scope, $dataProviderValue);
				$dataProviderMethod[] = $node->getStartLine();

				yield $dataProviderValue => $dataProviderMethod;
			}
		}

		if (!$this->phpunit10OrNewer) {
			return;
		}
		*/

		foreach ($node->getAttributes('PHPUnit\Framework\Attributes\DataProvider') as $attr) {
			$args = $attr->getArguments();
			if (count($args) !== 1) {
				continue;
			}

			yield [$args[0]];
		}
	}

}
