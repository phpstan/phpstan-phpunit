<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ConstantType;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\NodeAbstract>
 */
class AssertConstantActualRule implements \PHPStan\Rules\Rule
{

	public function getNodeType(): string
	{
		return \PhpParser\NodeAbstract::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!AssertRuleHelper::isMethodOrStaticCallOnAssert($node, $scope)) {
			return [];
		}

		/** @var \PhpParser\Node\Expr\MethodCall|\PhpParser\Node\Expr\StaticCall $node */
		$node = $node;

		if (count($node->getArgs()) < 2) {
			return [];
		}
		if (!$node->name instanceof Node\Identifier || strpos($node->name->name, 'assert') !== 0 ) {
			return [];
		}

		$actualType = $scope->getType($node->getArgs()[1]->value);

		if (!$actualType instanceof ConstantType ) {
			return [];
		}

		return [
			'The value of `$actual` should not be a constant',
		];
	}

}
