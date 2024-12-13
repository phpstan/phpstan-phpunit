<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use Countable;
use PhpParser\Node;
use PhpParser\Node\Expr\CallLike;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use function count;

/**
 * @implements Rule<CallLike>
 */
class AssertSameWithCountRule implements Rule
{

	public function getNodeType(): string
	{
		return CallLike::class;
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

		$right = $node->getArgs()[1]->value;

		if (
			$right instanceof Node\Expr\FuncCall
			&& $right->name instanceof Node\Name
			&& $right->name->toLowerString() === 'count'
		) {
			return [
				RuleErrorBuilder::message('You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, count($variable)).')
					->identifier('phpunit.assertCount')
					->build(),
			];
		}

		if (
			$right instanceof Node\Expr\MethodCall
			&& $right->name instanceof Node\Identifier
			&& $right->name->toLowerString() === 'count'
			&& count($right->getArgs()) === 0
		) {
			$type = $scope->getType($right->var);

			if ((new ObjectType(Countable::class))->isSuperTypeOf($type)->yes()) {
				return [
					RuleErrorBuilder::message('You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, $variable->count()).')
						->identifier('phpunit.assertCount')
						->build(),
				];
			}
		}

		return [];
	}

}
