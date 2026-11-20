<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\TestCase;
use function array_merge;
use function array_shift;
use function count;
use function is_string;
use function sprintf;

/**
 * @implements Rule<InClassNode>
 */
class ClassCoversExistsRule implements Rule
{

	/**
	 * Covers helper.
	 *
	 */
	private CoversHelper $coversHelper;

	/**
	 * Reflection provider.
	 *
	 */
	private ReflectionProvider $reflectionProvider;

	public function __construct(
		CoversHelper $coversHelper,
		ReflectionProvider $reflectionProvider
	)
	{
		$this->reflectionProvider = $reflectionProvider;
		$this->coversHelper = $coversHelper;
	}

	public function getNodeType(): string
	{
		return InClassNode::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		$classReflection = $node->getClassReflection();

		if (!$classReflection->is(TestCase::class)) {
			return [];
		}

		$classPhpDoc = $classReflection->getResolvedPhpDoc();
		[$classCovers, $classCoversDefaultClasses] = $this->coversHelper->getCoverAnnotations($classPhpDoc);

		if (count($classCoversDefaultClasses) >= 2) {
			return [
				RuleErrorBuilder::message(sprintf(
					'@coversDefaultClass is defined multiple times.',
				))->identifier('phpunit.coversDuplicate')->build(),
			];
		}

		$errors = [];
		$coversDefaultClass = array_shift($classCoversDefaultClasses);

		if ($coversDefaultClass !== null) {
			$className = (string) $coversDefaultClass->value;
			if (!$this->reflectionProvider->hasClass($className)) {
				$errors[] = RuleErrorBuilder::message(sprintf(
					'@coversDefaultClass references an invalid class %s.',
					$className,
				))->identifier('phpunit.coversClass')->build();
			}
		}

		foreach ($classCovers as $covers) {
			$errors = array_merge(
				$errors,
				$this->coversHelper->processCovers($node, $covers, null),
			);
		}

		return array_merge($errors, $this->processCoversAttributes($classReflection));
	}

	/**
	 * Checks the #[CoversClass], #[CoversTrait], #[CoversMethod] and #[CoversFunction] attributes.
	 *
	 * @return list<IdentifierRuleError>
	 */
	private function processCoversAttributes(ClassReflection $classReflection): array
	{
		$betterReflection = $classReflection->getNativeReflection()->getBetterReflection();
		$errors = [];

		foreach ($betterReflection->getAttributesByName('PHPUnit\Framework\Attributes\CoversClass') as $attribute) {
			$className = $this->getStringArgument($attribute->getArguments(), 0, 'className');
			if ($className === null) {
				continue;
			}

			if (!$this->reflectionProvider->hasClass($className)) {
				$errors[] = RuleErrorBuilder::message(sprintf(
					'#[CoversClass] value %s references an invalid class.',
					$className,
				))->identifier('phpunit.coversClass')->build();
				continue;
			}

			if (!$this->reflectionProvider->getClass($className)->isInterface()) {
				continue;
			}

			$errors[] = RuleErrorBuilder::message(sprintf(
				'#[CoversClass] value %s references an interface.',
				$className,
			))->identifier('phpunit.coversInterface')->build();
		}

		foreach ($betterReflection->getAttributesByName('PHPUnit\Framework\Attributes\CoversTrait') as $attribute) {
			$traitName = $this->getStringArgument($attribute->getArguments(), 0, 'traitName');
			if ($traitName === null) {
				continue;
			}

			if ($this->reflectionProvider->hasClass($traitName) && $this->reflectionProvider->getClass($traitName)->isTrait()) {
				continue;
			}

			$errors[] = RuleErrorBuilder::message(sprintf(
				'#[CoversTrait] value %s references an invalid trait.',
				$traitName,
			))->identifier('phpunit.coversTrait')->build();
		}

		foreach ($betterReflection->getAttributesByName('PHPUnit\Framework\Attributes\CoversMethod') as $attribute) {
			$arguments = $attribute->getArguments();
			$className = $this->getStringArgument($arguments, 0, 'className');
			$methodName = $this->getStringArgument($arguments, 1, 'methodName');
			if ($className === null || $methodName === null) {
				continue;
			}

			if (!$this->reflectionProvider->hasClass($className)) {
				$errors[] = RuleErrorBuilder::message(sprintf(
					'#[CoversMethod] value %s::%s references an invalid class.',
					$className,
					$methodName,
				))->identifier('phpunit.coversMethod')->build();
				continue;
			}

			if ($this->reflectionProvider->getClass($className)->hasMethod($methodName)) {
				continue;
			}

			$errors[] = RuleErrorBuilder::message(sprintf(
				'#[CoversMethod] value %s::%s references an invalid method.',
				$className,
				$methodName,
			))->identifier('phpunit.coversMethod')->build();
		}

		foreach ($betterReflection->getAttributesByName('PHPUnit\Framework\Attributes\CoversFunction') as $attribute) {
			$functionName = $this->getStringArgument($attribute->getArguments(), 0, 'functionName');
			if ($functionName === null) {
				continue;
			}

			if ($this->reflectionProvider->hasFunction(new Name($functionName), null)) {
				continue;
			}

			$errors[] = RuleErrorBuilder::message(sprintf(
				'#[CoversFunction] value %s references an invalid function.',
				$functionName,
			))->identifier('phpunit.coversFunction')->build();
		}

		return $errors;
	}

	/**
	 * @param array<int|string, mixed> $arguments
	 */
	private function getStringArgument(array $arguments, int $position, string $name): ?string
	{
		$value = $arguments[$position] ?? $arguments[$name] ?? null;

		return is_string($value) && $value !== '' ? $value : null;
	}

}
