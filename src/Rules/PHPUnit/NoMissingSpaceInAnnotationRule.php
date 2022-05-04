<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\TestCase;
use function in_array;
use function is_subclass_of;
use function preg_match;
use function preg_split;

/**
 * @implements Rule<Node>
 */
class NoMissingSpaceInAnnotationRule implements Rule
{

	private const ANNOTATIONS_WITH_PARAMS = [
		'backupGlobals',
		'backupStaticAttributes',
		'covers',
		'coversDefaultClass',
		'dataProvider',
		'depends',
		'group',
		'preserveGlobalState',
		'requires',
		'testDox',
		'testWith',
		'ticket',
		'uses',
	];

	public function getNodeType(): string
	{
		return Node::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node instanceof Node\Stmt\Class_ && !$node instanceof Node\Stmt\ClassMethod) {
			return [];
		}

		if ($node instanceof Node\Stmt\Class_) {
			if (is_subclass_of($node->namespacedName->toString(), TestCase::class) === false) {
				return [];
			}
		} else {
			$classReflection = $scope->getClassReflection();
			if ($classReflection === null || $classReflection->isSubclassOf(TestCase::class) === false) {
				return [];
			}
		}

		$docComment = $node->getDocComment();
		if ($docComment === null) {
			return [];
		}

		$errors = [];
		$docCommentLines = preg_split("/((\r?\n)|(\r\n?))/", $docComment->getText());
		if ($docCommentLines === false) {
			return [];
		}

		foreach ($docCommentLines as $docCommentLine) {
			// These annotations can't be retrieved using the getResolvedPhpDoc method on the FileTypeMapper as they are not present when they are invalid
			$annotation = preg_match('/@(?<property>[a-zA-Z]+)(?<whitespace>\s*)(?<value>.*)/', $docCommentLine, $matches);
			if ($annotation === false) {
				continue; // Line without annotation
			}

			/** @var array{property: string, whitespace: string, value: string} $matches */
			if (!in_array($matches['property'], self::ANNOTATIONS_WITH_PARAMS, true) || $matches['whitespace'] !== '') {
				continue;
			}

			$errors[] = 'Annotation "' . $matches[0] . '" is invalid, "@' . $matches['property'] . '" should be followed by a space and a value.';
		}

		return $errors;
	}

}
