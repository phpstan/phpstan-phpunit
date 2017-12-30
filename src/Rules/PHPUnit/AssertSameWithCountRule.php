<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

class AssertSameWithCountRule implements \PHPStan\Rules\Rule
{

	public function getNodeType(): string
	{
		return \PhpParser\NodeAbstract::class;
	}

	/**
	 * @param \PhpParser\Node\Expr\MethodCall|\PhpParser\Node\Expr\StaticCall $node
	 * @param \PHPStan\Analyser\Scope $scope
	 * @return string[] errors
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		if (!AssertRuleHelper::isMethodOrStaticCallOnTestCase($node, $scope)) {
			return [];
		}

		if (count($node->args) < 2) {
			return [];
		}
		if (!is_string($node->name) || strtolower($node->name) !== 'assertsame') {
			return [];
		}

		$right = $node->args[1]->value;

		if (
			$right instanceof Node\Expr\FuncCall
			&& $right->name instanceof Node\Name
		) {
			$method = strtolower($right->name->toString());
			$message = 'You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, %s($variable)).';

			if ($method === 'count') {
				return [
					sprintf($message, 'count'),
				];
			} elseif ($method === 'sizeof') {
				return [
					sprintf($message, 'sizeof'),
				];
			}
		}

		return [];
	}

}
