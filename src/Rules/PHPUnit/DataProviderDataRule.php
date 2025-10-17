<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\Expr\TypeExpr;
use PHPStan\Rules\Rule;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPUnit\Framework\TestCase;
use function array_slice;
use function count;
use function max;
use function min;

/**
 * @implements Rule<Node>
 */
class DataProviderDataRule implements Rule
{

	private TestMethodsHelper $testMethodsHelper;

	private DataProviderHelper $dataProviderHelper;

	public function __construct(
		TestMethodsHelper $testMethodsHelper,
		DataProviderHelper $dataProviderHelper
	)
	{
		$this->testMethodsHelper = $testMethodsHelper;
		$this->dataProviderHelper = $dataProviderHelper;
	}

	public function getNodeType(): string
	{
		return Node::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if ($scope->getFunction() === null) {
			return [];
		}
		if ($scope->isInAnonymousFunction()) {
			return [];
		}

		if (
			!$node instanceof Node\Stmt\Return_
			&& !$node instanceof Node\Expr\Yield_
			&& !$node instanceof Node\Expr\YieldFrom
		) {
			return [];
		}

		$arraysTypes = $this->buildArrayTypesFromNode($node, $scope);
		if ($arraysTypes === []) {
			return [];
		}

		$method = $scope->getFunction();
		$classReflection = $scope->getClassReflection();
		if (
			$classReflection === null
			|| !$classReflection->is(TestCase::class)
		) {
			return [];
		}

		$testsWithProvider = [];
		$testMethods = $this->testMethodsHelper->getTestMethods($classReflection, $scope);
		foreach ($testMethods as $testMethod) {
			foreach ($this->dataProviderHelper->getDataProviderMethods($scope, $testMethod, $classReflection) as [, $providerMethodName]) {
				if ($providerMethodName === $method->getName()) {
					$testsWithProvider[] = $testMethod;
					continue 2;
				}
			}
		}

		if (count($testsWithProvider) === 0) {
			return [];
		}

		$maxNumberOfParameters = 0;
		$trimArgs = count($testsWithProvider) > 1;
		foreach ($testsWithProvider as $testMethod) {
			$maxNumberOfParameters = max($maxNumberOfParameters, $testMethod->getNumberOfParameters());
		}

		foreach ($testsWithProvider as $testMethod) {
			$numberOfParameters = $testMethod->getNumberOfParameters();

			foreach ($arraysTypes as $arraysType) {
				$args = $this->arrayItemsToArgs($arraysType, $numberOfParameters);
				if ($args === null) {
					continue;
				}

				if ($trimArgs && $maxNumberOfParameters !== $numberOfParameters) {
					$args = array_slice($args, 0, min($numberOfParameters, $maxNumberOfParameters));
				}

				$scope->invokeNodeCallback(new Node\Expr\MethodCall(
					new TypeExpr(new ObjectType($classReflection->getName())),
					$testMethod->getName(),
					$args,
					['startLine' => $node->getStartLine()],
				));
			}
		}

		return [];
	}

	/**
	 * @return array<Node\Arg>
	 */
	private function arrayItemsToArgs(Type $array, int $numberOfParameters): ?array
	{
		$args = [];

		$constArrays = $array->getConstantArrays();
		if ($constArrays !== [] && count($constArrays) === 1) {
			$keyTypes = $constArrays[0]->getKeyTypes();
			$valueTypes = $constArrays[0]->getValueTypes();
		} elseif ($array->isArray()->yes()) {
			$keyTypes = [];
			$valueTypes = [];
			for ($i = 0; $i < $numberOfParameters; ++$i) {
				$keyTypes[$i] = $array->getIterableKeyType();
				$valueTypes[$i] = $array->getIterableValueType();
			}
		} else {
			return null;
		}

		foreach ($valueTypes as $i => $valueType) {
			$key = $keyTypes[$i]->getConstantStrings();
			if (count($key) > 1) {
				return null;
			}

			if (count($key) === 0) {
				$arg = new Node\Arg(new TypeExpr($valueType));
				$args[] = $arg;
				continue;

			}

			$arg = new Node\Arg(
				new TypeExpr($valueType),
				false,
				false,
				[],
				new Node\Identifier($key[0]->getValue()),
			);
			$args[] = $arg;
		}

		return $args;
	}

	/**
	 * @param Node\Stmt\Return_|Node\Expr\Yield_|Node\Expr\YieldFrom $node
	 * @return array<Type>
	 */
	private function buildArrayTypesFromNode(Node $node, Scope $scope): array
	{
		$arraysTypes = [];
		if ($node instanceof Node\Stmt\Return_ || $node instanceof Node\Expr\YieldFrom) {
			if ($node->expr === null) {
				return [];
			}

			$exprType = $scope->getType($node->expr);
			$exprConstArrays = $exprType->getConstantArrays();
			foreach ($exprConstArrays as $constArray) {
				foreach ($constArray->getValueTypes() as $valueType) {
					foreach ($valueType->getConstantArrays() as $constValueArray) {
						$arraysTypes[] = $constValueArray;
					}
				}
			}

			if ($arraysTypes === []) {
				$arraysTypes = $exprType->getIterableValueType()->getArrays();
			}
		} elseif ($node instanceof Node\Expr\Yield_) {
			if ($node->value === null) {
				return [];
			}

			$exprType = $scope->getType($node->value);
			$arraysTypes = $exprType->getConstantArrays();
		}

		return $arraysTypes;
	}

}
