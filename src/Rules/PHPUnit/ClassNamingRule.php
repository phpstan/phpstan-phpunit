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
use function strlen;
use function substr_compare;

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

		$class = $this->reflectionProvider->getClass($node->namespacedName->toString());

		if (!$class->isSubclassOf(TestCase::class)) {
			return [];
		}

		$errors = [];

		if ($class->isAbstract()) {
			$this->requireSuffix(
				$errors,
				$class->getName(),
				'TestCase',
				'Abstract test case class, \'%s\', should be named ending in \'%s\'.',
			);

			return $errors;
		}

		$this->requireSuffix(
			$errors,
			$class->getName(),
			'Test',
			'Concrete test class, \'%s\', should be named ending in \'%s\'.',
		);

		if (!$class->isFinal()) {
			$errors[] = RuleErrorBuilder::message(sprintf(
				'Concrete test class, \'%s\', should be declared final.',
				$class->getName(),
			))->identifier('phpunit.naming')->build();
		}

		return $errors;
	}

	/**
	 * @param list<IdentifierRuleError> $errors
	 * @param class-string $className
	 * @param non-empty-string $suffix
	 */
	private function requireSuffix(array &$errors, string $className, string $suffix, string $messageFormat): void
	{
		if ($this->hasSuffix($className, $suffix)) {
			return;
		}

		$errors[] = RuleErrorBuilder::message(sprintf(
			$messageFormat,
			$className,
			$suffix,
		))->identifier('phpunit.naming')->build();
	}

	/**
	 * Checks if class name has the given suffix.
	 *
	 * Comparison is case insensitive.
	 *
	 * @param class-string $className
	 * @param non-empty-string $suffix
	 */
	private function hasSuffix(string $className, string $suffix): bool
	{
		$classNameLen = strlen($className);
		$suffixLen = strlen($suffix);

		return $suffixLen < $classNameLen
			&& substr_compare($className, $suffix, -$suffixLen, $suffixLen, true) === 0;
	}

}
