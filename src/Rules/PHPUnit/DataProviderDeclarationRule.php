<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\FileTypeMapper;
use PHPUnit\Framework\TestCase;
use function array_merge;

/**
 * @implements Rule<Node\Stmt\ClassMethod>
 */
class DataProviderDeclarationRule implements Rule
{

	/**
	 * Data provider helper.
	 *
	 * @var DataProviderHelper
	 */
	private $dataProviderHelper;

	/**
	 * The file type mapper.
	 *
	 * @var FileTypeMapper
	 */
	private $fileTypeMapper;

	public function __construct(
		DataProviderHelper $dataProviderHelper,
		FileTypeMapper $fileTypeMapper
	)
	{
		$this->dataProviderHelper = $dataProviderHelper;
		$this->fileTypeMapper = $fileTypeMapper;
	}

	public function getNodeType(): string
	{
		return Node\Stmt\ClassMethod::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		$classReflection = $scope->getClassReflection();

		if ($classReflection === null || !$classReflection->isSubclassOf(TestCase::class)) {
			return [];
		}

		$docComment = $node->getDocComment();
		if ($docComment === null) {
			return [];
		}

		$methodPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
			$scope->getFile(),
			$classReflection->getName(),
			$scope->isInTrait() ? $scope->getTraitReflection()->getName() : null,
			$node->name->toString(),
			$docComment->getText()
		);

		$annotations = $this->dataProviderHelper->getDataProviderAnnotations($methodPhpDoc);

		$errors = [];

		foreach ($annotations as $annotation) {
			$errors = array_merge(
				$errors,
				$this->dataProviderHelper->processDataProvider($scope, $annotation)
			);
		}

		return $errors;
	}

}
