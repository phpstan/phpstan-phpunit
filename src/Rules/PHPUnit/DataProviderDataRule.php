<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use LogicException;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Php\PhpMethodFromParserNodeReflection;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\TestCase;

/**
 * @implements Rule<Node\Stmt\Return_>
 */
class DataProviderDataRule implements Rule
{
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

		if ($classReflection === null || !$classReflection->is(TestCase::class)) {
			return [];
		}

		// XXX check whether the method is used as a data provider

		if (method_exists($scope, 'invokeNodeCallback')) {
			foreach($node->expr->items as $item) {
				if (!$item->value instanceof Node\Expr\Array_) {
					return [];
				}

				$args = $this->arrayItemsToArgs($item->value);
				$var = new Node\Expr\New_(new Node\Name('test'));
				$scope->invokeNodeCallback(new Node\Expr\MethodCall(
					$var,
					'testTrim',
					$args,
					['startLine' => $item->getStartLine()]
				));
			}
		}

		return [];
	}

	/**
	 * @return array<Node\Arg>
	 */
	private function arrayItemsToArgs(Node\Expr\Array_ $array): ?array {
		$args = [];

		foreach($array->items as $item) {
			// XXX named args
			$value = $item->value;

			$arg = new Node\Arg($value);
			$args[] = $arg;
		}

		return $args;
	}

}
