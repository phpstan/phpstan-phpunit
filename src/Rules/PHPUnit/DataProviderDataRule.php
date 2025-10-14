<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Php\PhpMethodFromParserNodeReflection;
use PHPStan\Rules\Rule;
use PHPStan\ShouldNotHappenException;
use PHPUnit\Framework\TestCase;
use function count;

/**
 * @implements Rule<Node\Stmt\Return_>
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
			if (!$node->expr instanceof Node\Expr\Array_) {
				return [];
			}

			$arrayExprs = [];
			foreach ($node->expr->items as $item) {
				if (!$item->value instanceof Node\Expr\Array_) {
					return [];
				}
				$arrayExprs[] = $item->value;
			}
		} elseif ($node instanceof Node\Expr\Yield_) {
			if (!$node->value instanceof Node\Expr\Array_) {
				return [];
			}
			$arrayExprs = [$node->value];
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
		if (!$method instanceof PhpMethodFromParserNodeReflection) {
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
		$testMethods = $this->testMethodsHelper->getTestMethods($classReflection);
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

		foreach ($arrayExprs as $arrayExpr) {
			if (!$arrayExpr instanceof Node\Expr\Array_) {
				throw new ShouldNotHappenException();
			}

			$args = $this->arrayItemsToArgs($arrayExpr);
			if ($args === null) {
				continue;
			}

			$var = new Node\Expr\New_(new Node\Name($classReflection->getName()));
			$scope->invokeNodeCallback(new Node\Expr\MethodCall(
				$var,
				$testsWithProvider[0]->getName(),
				$args,
				['startLine' => $arrayExpr->getStartLine()],
			));
		}

		return [];
	}

	/**
	 * @return array<Node\Arg>
	 */
	private function arrayItemsToArgs(Node\Expr\Array_ $array): ?array
	{
		$args = [];

		foreach ($array->items as $item) {
			// XXX named args
			$value = $item->value;

			$arg = new Node\Arg($value);
			$args[] = $arg;
		}

		return $args;
	}

}
