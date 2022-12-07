<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Analyser\Scope;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use function array_merge;
use function preg_match;
use function sprintf;
use function trim;

class DataProviderHelper
{

	/**
	 * @return array<PhpDocTagNode>
	 */
	public function getDataProviderAnnotations(?ResolvedPhpDocBlock $phpDoc): array
	{
		if ($phpDoc === null) {
			return [];
		}

		$phpDocNodes = $phpDoc->getPhpDocNodes();

		$annotations = [];

		foreach ($phpDocNodes as $docNode) {
			$annotations = array_merge(
				$annotations,
				$docNode->getTagsByName('@dataProvider')
			);
		}

		return $annotations;
	}

	/**
	 * @return RuleError[] errors
	 */
	public function processDataProvider(
		Scope $scope,
		PhpDocTagNode $phpDocTag
	): array
	{
		$dataProviderName = $this->getDataProviderName($phpDocTag);
		if ($dataProviderName === null) {
			// Missing name is already handled in NoMissingSpaceInMethodAnnotationRule
			return [];
		}

		$classReflection = $scope->getClassReflection();
		if ($classReflection === null) {
			// Should not happen
			return [];
		}

		try {
			$dataProviderMethodReflection = $classReflection->getNativeMethod($dataProviderName);
		} catch (MissingMethodFromReflectionException $missingMethodFromReflectionException) {
			$error = RuleErrorBuilder::message(sprintf(
				'@dataProvider %s related method not found.',
				$dataProviderName
			))->build();

			return [$error];
		}

		$errors = [];

		if ($dataProviderName !== $dataProviderMethodReflection->getName()) {
			$errors[] = RuleErrorBuilder::message(sprintf(
				'@dataProvider %s related method is used with incorrect case: %s.',
				$dataProviderName,
				$dataProviderMethodReflection->getName()
			))->build();
		}

		if (!$dataProviderMethodReflection->isPublic()) {
			$errors[] = RuleErrorBuilder::message(sprintf(
				'@dataProvider %s related method must be public.',
				$dataProviderName
			))->build();
		}

		if (!$dataProviderMethodReflection->isStatic()) {
			$errors[] = RuleErrorBuilder::message(sprintf(
				'@dataProvider %s related method must be static.',
				$dataProviderName
			))->build();
		}

		return $errors;
	}

	private function getDataProviderName(PhpDocTagNode $phpDocTag): ?string
	{
		$value = trim((string) $phpDocTag->value);

		if (preg_match('/^[\S]+/', $value, $matches) !== 1) {
			return null;
		}

		return $matches[0];
	}

}
