<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use Countable;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use function count;

/**
 * @implements Rule<NodeAbstract>
 */
class AssertSameWithCountRule implements Rule
{

	/** @var bool */
	private $bleedingEdge;

	public function __construct(bool $bleedingEdge)
	{
		$this->bleedingEdge = $bleedingEdge;
	}

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

		$right = $node->getArgs()[1]->value;

		$rightIsCountFuncCall = $this->isCountFuncCall($right);
		$rightIsCountMethodCall = $this->isCountMethodCall($right) && $this->argIsCountable($right, $scope);
		if (!($rightIsCountFuncCall || $rightIsCountMethodCall)) {
			return [];
		}

		$leftIsCountFuncCall = $leftIsCountMethodCall = false;
		if ($this->bleedingEdge) {
			$left = $node->getArgs()[0]->value;
			$leftIsCountFuncCall = $this->isCountFuncCall($left);
			$leftIsCountMethodCall = $this->isCountMethodCall($left) && $this->argIsCountable($left, $scope);
		}

		if ($rightIsCountFuncCall) {
			if ($leftIsCountFuncCall) {
				return [
					RuleErrorBuilder::message('You should use assertSameSize($expected, $variable) instead of assertSame(count($expected), count($variable)).')
						->identifier('phpunit.assertSameSize')
						->build(),
				];
			} elseif ($leftIsCountMethodCall) {
				return [
					RuleErrorBuilder::message('You should use assertSameSize($expected, $variable) instead of assertSame($expected->count(), count($variable)).')
						->identifier('phpunit.assertSameSize')
						->build(),
				];
			}

			return [
				RuleErrorBuilder::message('You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, count($variable)).')
					->identifier('phpunit.assertCount')
					->build(),
			];
		}

		if ($leftIsCountFuncCall) {
			return [
				RuleErrorBuilder::message('You should use assertSameSize($expected, $variable) instead of assertSame(count($expected), $variable->count()).')
					->identifier('phpunit.assertSameSize')
					->build(),
			];
		} elseif ($leftIsCountMethodCall) {
			return [
				RuleErrorBuilder::message('You should use assertSameSize($expected, $variable) instead of assertSame($expected->count(), $variable->count()).')
					->identifier('phpunit.assertSameSize')
					->build(),
			];
		}

		return [
			RuleErrorBuilder::message('You should use assertCount($expectedCount, $variable) instead of assertSame($expectedCount, $variable->count()).')
				->identifier('phpunit.assertCount')
				->build(),
		];
	}

	/**
	 * @phpstan-assert-if-true Node\Expr\FuncCall $expr
	 */
	private function isCountFuncCall(Node\Expr $expr): bool
	{
		return $expr instanceof Node\Expr\FuncCall
			&& $expr->name instanceof Node\Name
			&& $expr->name->toLowerString() === 'count';
	}

	/**
	 * @phpstan-assert-if-true Node\Expr\MethodCall $expr
	 */
	private function isCountMethodCall(Node\Expr $expr): bool
	{
		return $expr instanceof Node\Expr\MethodCall
			&& $expr->name instanceof Node\Identifier
			&& $expr->name->toLowerString() === 'count'
			&& count($expr->getArgs()) === 0;
	}

	private function argIsCountable(MethodCall $methodCall, Scope $scope): bool
	{
		$type = $scope->getType($methodCall->var);
		$countableType = new ObjectType(Countable::class);

		return $countableType->isSuperTypeOf($type)->yes();
	}

}
