<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\TestCase;
use function sprintf;
use function str_ends_with;

/**
 * @implements Rule<Node\Stmt\Class_>
 */
class ClassNamingRule implements Rule
{

	private ReflectionProvider $reflectionProvider;

	public function __construct(ReflectionProvider $reflectionProvider)
	{
		$this->reflectionProvider = $reflectionProvider;
	}

	public function getNodeType(): string
	{
		return Node\Stmt\Class_::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!isset($node->namespacedName)) {
			return [];
		}

		$className = $node->namespacedName->name;
		$class = $this->reflectionProvider->getClass($className);

		if (!$class->isSubclassOf(TestCase::class)) {
			return [];
		}

		$errors = [];

		if ($class->isAbstract()) {
			$this->requireSuffix(
				$errors,
				$className,
				'TestCase',
				'Abstract test case class, \'%s\', should be named ending in \'%s\'.',
			);

			return $errors;
		}

		$this->requireSuffix(
			$errors,
			$className,
			'Test',
			'Concrete test class, \'%s\', should be named ending in \'%s\'.',
		);

		if (!$class->isFinal()) {
			$errors[] = RuleErrorBuilder::message(sprintf(
				'Concrete test class, \'%s\', should be declared final.',
				$className,
			))->identifier('phpunit.naming')->build();
		}

		return $errors;
	}

	/**
	 * @param list<IdentifierRuleError> $errors
	 */
	private function requireSuffix(array &$errors, string $className, string $suffix, string $messageFormat): void
	{
		if (str_ends_with($className, $suffix)) {
			return;
		}

		// @todo Case sensitivity??
		$errors[] = RuleErrorBuilder::message(sprintf(
			$messageFormat,
			$className,
			$suffix,
		))->identifier('phpunit.naming')->build();
	}

}
