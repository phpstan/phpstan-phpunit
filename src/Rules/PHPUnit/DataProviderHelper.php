<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Analyser\Scope;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\UnionType;
use PHPStan\Type\VerbosityLevel;
use function array_merge;
use function count;
use function preg_match;
use function sprintf;

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
		PhpDocTagNode $phpDocTag,
		bool $checkFunctionNameCase,
		bool $deprecationRulesInstalled,
		?ExtendedMethodReflection $testMethodReflection
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

		if ($checkFunctionNameCase && $dataProviderName !== $dataProviderMethodReflection->getName()) {
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

		if ($deprecationRulesInstalled && !$dataProviderMethodReflection->isStatic()) {
			$errors[] = RuleErrorBuilder::message(sprintf(
				'@dataProvider %s related method must be static.',
				$dataProviderName
			))->build();
		}

		$dataProviderParameterAcceptor = ParametersAcceptorSelector::selectSingle($dataProviderMethodReflection->getVariants());
		$providerReturnType = $dataProviderParameterAcceptor->getReturnType();
		if ($testMethodReflection !== null && $providerReturnType->isIterable()->yes()) {
			$collectionType = $providerReturnType->getIterableValueType();

			if ($collectionType->isIterable()->yes()) {
				$testParameterAcceptor = ParametersAcceptorSelector::selectSingle($testMethodReflection->getVariants());

				$valueType = $collectionType->getIterableValueType();

				if ($valueType instanceof UnionType) {
					if (count($valueType->getTypes()) !== count($testParameterAcceptor->getParameters())) {
						$errors[] = RuleErrorBuilder::message(sprintf(
							'@dataProvider %s returns a different number of values the test method expects.',
							$dataProviderName
						))->build();

						return $errors;
					}

					foreach ($valueType->getTypes() as $i => $innerType) {
						if (!$testParameterAcceptor->getParameters()[$i]->getType()->accepts($innerType, $scope->isDeclareStrictTypes())->yes()) {
							$errors[] = RuleErrorBuilder::message(sprintf(
								'@dataProvider %s returns %s which is not compatible with the test method parameters.',
								$dataProviderName,
								$providerReturnType->describe(VerbosityLevel::precise())
							))->build();

							return $errors;
						}
					}
				} else {
					if (count($testParameterAcceptor->getParameters()) !== 1) {
						$errors[] = RuleErrorBuilder::message(sprintf(
							'@dataProvider %s returns a different number of values the test method expects.',
							$dataProviderName
						))->build();

						return $errors;
					}

					if (!$testParameterAcceptor->getParameters()[0]->getType()->accepts($valueType, $scope->isDeclareStrictTypes())->yes()) {
						$errors[] = RuleErrorBuilder::message(sprintf(
							'@dataProvider %s returns %s which is not compatible with the test method parameters.',
							$dataProviderName,
							$providerReturnType->describe(VerbosityLevel::precise())
						))->build();
					}
				}
			}
		}

		return $errors;
	}

	private function getDataProviderName(PhpDocTagNode $phpDocTag): ?string
	{
		if (preg_match('/^[^ \t]+/', (string) $phpDocTag->value, $matches) !== 1) {
			return null;
		}

		return $matches[0];
	}

}
