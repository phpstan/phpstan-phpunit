<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\ConstFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use function count;

/**
 * @implements Rule<CallLike>
 */
class AssertSameBooleanExpectedRule implements Rule
{

	public function getNodeType(): string
	{
		return CallLike::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node instanceof Node\Expr\MethodCall && ! $node instanceof Node\Expr\StaticCall) {
			return [];
		}
		if (count($node->getArgs()) < 2) {
			return [];
		}
		if ($node->isFirstClassCallable()) {
			return [];
		}
		if (!$node->name instanceof Node\Identifier || $node->name->toLowerString() !== 'assertsame') {
			return [];
		}

		$expectedArgumentValue = $node->getArgs()[0]->value;
		if (!($expectedArgumentValue instanceof ConstFetch)) {
			return [];
		}

		if (!AssertRuleHelper::isMethodOrStaticCallOnAssert($node, $scope)) {
			return [];
		}

		if ($expectedArgumentValue->name->toLowerString() === 'true') {
			return [
				RuleErrorBuilder::message('You should use assertTrue() instead of assertSame() when expecting "true"')->identifier('phpunit.assertTrue')->build(),
			];
		}

		if ($expectedArgumentValue->name->toLowerString() === 'false') {
			return [
				RuleErrorBuilder::message('You should use assertFalse() instead of assertSame() when expecting "false"')->identifier('phpunit.assertFalse')->build(),
			];
		}

		return [];
	}

}
