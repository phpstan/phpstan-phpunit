<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ConstantType;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\NodeAbstract>
 */
class AssertConstantActualRule implements \PHPStan\Rules\Rule
{

	/** @var array<int, string> */
	private static $affectedMethodPrefixes = [
		'assertEquals',
		'assertFileEquals',
		'assertFileNotEquals',
		'assertGreaterThan',
		'assertInstanceOf',
		'assertLessThan',
		'assertNotEquals',
		'assertNotInstanceOf',
		'assertNotSame',
		'assertObjectEquals',
		'assertSame',
		'assertStringEqualsFile',
		'assertStringNotEqualsFile',
	];

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
		if (!$node->name instanceof Node\Identifier || !$this->isAffectedMethod($node->name)) {
			return [];
		}

		$actualType = $scope->getType($node->getArgs()[1]->value);

		if (!$actualType instanceof ConstantType) {
			return [];
		}

		return [
			'The value of `$actual` should not be a constant',
		];
	}

	private function isAffectedMethod(Identifier $identifier): bool
	{
		foreach (self::$affectedMethodPrefixes as $prefix) {
			if (strpos($identifier->name, $prefix) === 0) {
				return true;
			}
		}

		return false;
	}

}
