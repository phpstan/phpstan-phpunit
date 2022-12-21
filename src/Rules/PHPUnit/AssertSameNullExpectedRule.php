<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\NodeAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use function count;

/**
 * @implements Rule<NodeAbstract>
 */
class AssertSameNullExpectedRule implements Rule
{

	public function getNodeType(): string
	{
		return NodeAbstract::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!AssertRuleHelper::isMethodOrStaticCallOnAssert($node, $scope)) {
			return [];
		}

		if (count($node->getArgs()) < 2) {
			return [];
		}
		if (!$node->name instanceof Node\Identifier || $node->name->toLowerString() !== 'assertsame') {
			return [];
		}

		$expectedArgumentValue = $node->getArgs()[0]->value;
		if (!($expectedArgumentValue instanceof ConstFetch)) {
			return [];
		}

		if ($expectedArgumentValue->name->toLowerString() === 'null') {
			return [
				'You should use assertNull() instead of assertSame(null, $actual).',
			];
		}

		return [];
	}

}
