<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use Nette\Utils\Strings;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
class UnitExtendsFromTestCaseRule implements \PHPStan\Rules\Rule
{

	private const ALLOWED_EXTENDED_CLASSES = [
		\PHPUnit\Framework\TestCase::class,
		\PHPStan\Testing\RuleTestCase::class,
	];

	public function getNodeType(): string
	{
		return Node\Stmt\Class_::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		/** @var Node\Stmt\Class_ $node */

		$namespace = $scope->getNamespace();
		if ($namespace === null
			|| !Strings::contains($namespace, 'Unit')
			|| !Strings::endsWith((string) $node->name, 'Test')
		) { // only unit tests are considered
			return [];
		}

		$extendedClass = $node->extends;
		if ($extendedClass === null || !in_array($extendedClass->toString(), self::ALLOWED_EXTENDED_CLASSES, true)) {
			return [
				sprintf(
					'You should only extend from one of the following classes in unit tests: "%s".',
					implode(', ', self::ALLOWED_EXTENDED_CLASSES)
				),
			];
		}

		return [];
	}

}
