<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Php\PhpMethodFromParserNodeReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\TestCase;
use function count;

/**
 * @implements Rule<Node\Stmt\Return_>
 */
class DataProviderDataRule implements Rule
{

	private ReflectionProvider $reflectionProvider;

	private TestMethodsHelper $testMethodsHelper;

	public function __construct(
		ReflectionProvider $reflectionProvider,
		TestMethodsHelper $testMethodsHelper
	)
	{
		$this->reflectionProvider = $reflectionProvider;
		$this->testMethodsHelper = $testMethodsHelper;
	}

	public function getNodeType(): string
	{
		return Node\Stmt\Return_::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node->expr instanceof Node\Expr\Array_) {
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
			foreach ($this->testMethodsHelper->getDataProviderMethods($scope, $testMethod, $classReflection) as [$providerMethod]) {
				if ($providerMethod === $method->getName()) {
					$testsWithProvider[] = $testMethod;
					continue 2;
				}
			}
		}

		if (count($testsWithProvider) === 0) {
			return [];
		}

		foreach ($node->expr->items as $item) {
			if (!$item->value instanceof Node\Expr\Array_) {
				return [];
			}

			$args = $this->arrayItemsToArgs($item->value);
			$var = new Node\Expr\New_(new Node\Name($classReflection->getName()));
			$scope->invokeNodeCallback(new Node\Expr\MethodCall(
				$var,
				$testsWithProvider[0]->getName(),
				$args,
				['startLine' => $item->getStartLine()],
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
