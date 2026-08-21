<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use function count;
use function in_array;
use function sprintf;
use function strtolower;

/**
 * @implements Rule<CallLike>
 */
class AssertEmptyIsDiscouragedRule implements Rule
{

	public function getNodeType(): string
	{
		return CallLike::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if ($node->isFirstClassCallable() || count($node->getArgs()) < 1) {
			return [];
		}

		if ($node instanceof MethodCall || $node instanceof StaticCall) {
			if (!$node->name instanceof Identifier || !in_array($node->name->toLowerString(), ['assertempty', 'assertnotempty'], true)) {
				return [];
			}
			if (!AssertRuleHelper::isMethodOrStaticCallOnAssert($node, $scope)) {
				return [];
			}
		} elseif ($node instanceof FuncCall) {
			if (!$node->name instanceof Name || !in_array(strtolower($scope->resolveName($node->name)), ['phpunit\\framework\\assertempty', 'phpunit\\framework\\assertnotempty'], true)) {
				return [];
			}
		} else {
			return [];
		}

		return [
			RuleErrorBuilder::message(sprintf(
				'%s() is not allowed. Use more strict assertion.',
				$node instanceof FuncCall ? $node->name->getLast() : $node->name->toString(),
			))
				->identifier('empty.notAllowed')
				->build(),
		];
	}

}
