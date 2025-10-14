<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\Expr\TypeExpr;
use PHPStan\Rules\Rule;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\ObjectType;
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
		if ($node instanceof Node\Stmt\Return_ || $node instanceof Node\Expr\YieldFrom) {
			if ($node->expr === null) {
				return [];
			}

			$exprType = $scope->getType($node->expr);
			if (!$exprType->isConstantArray()->yes()) {
				return [];
			}

			$constArrays = $exprType->getConstantArrays();
			$constantArrays = [];
			foreach ($constArrays as $constArray) {
				foreach ($constArray->getValueTypes() as $valueType) {
					foreach ($valueType->getConstantArrays() as $constValueArray) {
						$constantArrays[] = $constValueArray;
					}
				}
			}
		} elseif ($node instanceof Node\Expr\Yield_) {
			if ($node->value === null) {
				return [];
			}

			$exprType = $scope->getType($node->value);
			if (!$exprType->isConstantArray()->yes()) {
				return [];
			}

			$constantArrays = $exprType->getConstantArrays();
		} else {
			return [];
		}

		if ($scope->getFunction() === null) {
			return [];
		}

		if ($scope->isInAnonymousFunction()) {
			return [];
		}

		$method = $scope->getFunction();
		if ($method === null) {
			return [];
		}

		$classReflection = $scope->getClassReflection();
		if (
			$classReflection === null
			|| !$classReflection->is(TestCase::class)
			|| $classReflection->isAbstract()
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
			foreach ($constantArrays as $constantArray) {
				$args = $this->arrayItemsToArgs($constantArray);
				if ($args === null) {
					continue;
				}

				if ($trimArgs && $maxNumberOfParameters !== $testMethod->getNumberOfParameters()) {
					$args = array_slice($args, 0, min($testMethod->getNumberOfParameters(), $maxNumberOfParameters));
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
	private function arrayItemsToArgs(ConstantArrayType $array): ?array
	{
		$args = [];

		$keyTypes = $array->getKeyTypes();
		foreach ($array->getValueTypes() as $i => $valueType) {
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

}
