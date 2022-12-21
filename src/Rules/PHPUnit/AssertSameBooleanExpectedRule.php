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
class AssertSameBooleanExpectedRule implements Rule
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

		if ($expectedArgumentValue->name->toLowerString() === 'true') {
			return [
				'You should use assertTrue() instead of assertSame() when expecting "true"',
			];
		}

		if ($expectedArgumentValue->name->toLowerString() === 'false') {
			return [
				'You should use assertFalse() instead of assertSame() when expecting "false"',
			];
		}

		return [];
	}

}
