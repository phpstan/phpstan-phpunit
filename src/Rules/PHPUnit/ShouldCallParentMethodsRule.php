<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\TestCase;
use function in_array;
use function sprintf;
use function strtolower;

/**
 * @implements Rule<InClassMethodNode>
 */
class ShouldCallParentMethodsRule implements Rule
{

	public function getNodeType(): string
	{
		return InClassMethodNode::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		$methodName = $node->getOriginalNode()->name->name;
		if (!in_array(strtolower($methodName), ['setup', 'teardown'], true)) {
			return [];
		}
		if ($scope->getClassReflection() === null) {
			return [];
		}

		if (!$scope->getClassReflection()->is(TestCase::class)) {
			return [];
		}

		$parentClass = $scope->getClassReflection()->getParentClass();

		if ($parentClass === null) {
			return [];
		}
		if (!$parentClass->hasNativeMethod($methodName)) {
			return [];
		}

		$parentMethod = $parentClass->getNativeMethod($methodName);
		if ($parentMethod->getDeclaringClass()->getName() === TestCase::class) {
			return [];
		}

		$hasParentCall = $this->hasParentClassCall($node->getOriginalNode()->getStmts(), strtolower($methodName));

		if (!$hasParentCall) {
			return [
				RuleErrorBuilder::message(
					sprintf('Missing call to parent::%s() method.', $methodName),
				)->identifier('phpunit.callParent')->build(),
			];
		}

		return [];
	}

	/**
	 * @param Node\Stmt[]|null $stmts
	 */
	private function hasParentClassCall(?array $stmts, string $methodName): bool
	{
		if ($stmts === null) {
			return false;
		}

		$visitor = new class ($methodName) extends NodeVisitorAbstract {

			private string $methodName;

			public bool $found = false;

			public function __construct(string $methodName)
			{
				$this->methodName = $methodName;
			}

			public function enterNode(Node $node): ?int
			{
				if ($node instanceof Node\FunctionLike || $node instanceof Node\Stmt\ClassLike) {
					return NodeVisitor::DONT_TRAVERSE_CHILDREN;
				}

				if (
					$node instanceof Node\Expr\StaticCall
					&& $node->class instanceof Node\Name
					&& $node->class->toLowerString() === 'parent'
					&& $node->name instanceof Node\Identifier
					&& $node->name->toLowerString() === $this->methodName
				) {
					$this->found = true;

					return NodeVisitor::STOP_TRAVERSAL;
				}

				return null;
			}

		};

		$traverser = new NodeTraverser($visitor);
		$traverser->traverse($stmts);

		return $visitor->found;
	}

}
