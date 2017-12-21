<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\VerbosityLevel;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\NodeAbstract>
 */
class AssertEqualsIsDiscouragedRule implements \PHPStan\Rules\Rule
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

		if (count($node->args) < 2) {
			return [];
		}
		if (!$node->name instanceof Node\Identifier || strtolower($node->name->name) !== 'assertequals') {
			return [];
		}

		$leftType = $scope->getType($node->args[0]->value);
		$rightType = $scope->getType($node->args[1]->value);

		if (
			($leftType instanceof BooleanType && $rightType instanceof BooleanType)
			|| ($leftType instanceof IntegerType && $rightType instanceof IntegerType)
			|| ($leftType instanceof StringType && $rightType instanceof StringType)
		) {
			$typeDescription = $leftType->describe(VerbosityLevel::typeOnly());
			if ($leftType instanceof BooleanType) {
				$typeDescription = 'bool';
			}
			return [
				sprintf(
					'You should use assertSame instead of assertEquals, because both values are of the same type "%s"',
					$typeDescription
				),
			];
		}
		if (
			($leftType instanceof FloatType && $rightType instanceof FloatType)
			&& count($node->args) < 4 // is not using delta for comparing floats
		) {
			return [
				'You should use assertSame instead of assertEquals, because both values are of the same type "float" and you are not using $delta argument',
			];
		}

		if (!$node->hasAttribute('comments')) {
			return [
				'You should use assertSame instead of assertEquals. Or it should have a comment above with explanation: // assertEquals because ...',
			];
		}

		/** @var \PhpParser\Comment[] $comments */
		$comments = $node->getAttribute('comments');
		$comment = $comments[count($comments) - 1];

		// the comment should be on the line above the assertEquals()
		if ($comment->getLine() !== ($node->getLine() - 1)) {
			return [
				'You should use assertSame instead of assertEquals. Or it should have a comment above with explanation: // assertEquals because ... (The comment is not directly above the assertEquals)',
			];
		}

		if (preg_match('~^//\s+assertEquals because(.*)~', $comment->getReformattedText()) === 0) {
			return [
				'You should use assertSame instead of assertEquals. Or it should have a comment above with explanation: // assertEquals because ... (There is a different comment)',
			];
		}

		return [];
	}

}
