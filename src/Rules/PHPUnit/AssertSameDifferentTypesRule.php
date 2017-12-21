<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

class AssertSameDifferentTypesRule implements \PHPStan\Rules\Rule
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

		$leftType = $scope->getType($node->args[0]->value);
		$rightType = $scope->getType($node->args[1]->value);

		if ($leftType->isSuperTypeOf($rightType)->no()) {
			return [
				sprintf(
					'Call to assertSame() with different types %s and %s will always result in test failure.',
					$leftType->describe(),
					$rightType->describe()
				),
			];
		}

		return [];
	}

}
