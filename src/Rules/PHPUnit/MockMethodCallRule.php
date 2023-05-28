<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use function array_filter;
use function count;
use function implode;
use function in_array;
use function sprintf;

/**
 * @implements Rule<MethodCall>
 */
class MockMethodCallRule implements Rule
{

	public function getNodeType(): string
	{
		return Node\Expr\MethodCall::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node->name instanceof Node\Identifier || $node->name->name !== 'method') {
			return [];
		}

		if (count($node->getArgs()) < 1) {
			return [];
		}

		$argType = $scope->getType($node->getArgs()[0]->value);
		if (count($argType->getConstantStrings()) === 0) {
			return [];
		}

		$errors = [];
		foreach ($argType->getConstantStrings() as $constantString) {
			$method = $constantString->getValue();
			$type = $scope->getType($node->var);

			if (
				(
					in_array(MockObject::class, $type->getObjectClassNames(), true)
					|| in_array(Stub::class, $type->getObjectClassNames(), true)
				)
				&& !$type->hasMethod($method)->yes()
			) {
				$mockClasses = array_filter($type->getObjectClassNames(), static function (string $class): bool {
					return $class !== MockObject::class && $class !== Stub::class;
				});
				if (count($mockClasses) === 0) {
					continue;
				}

				$errors[] = RuleErrorBuilder::message(sprintf(
					'Trying to mock an undefined method %s() on class %s.',
					$method,
					implode('&', $mockClasses)
				))->identifier('phpunit.mockMethod')->build();
				continue;
			}

			$mockedClassObject = $type->getTemplateType(InvocationMocker::class, 'TMockedClass');
			if ($mockedClassObject->hasMethod($method)->yes()) {
				continue;
			}

			$classNames = $mockedClassObject->getObjectClassNames();
			if (count($classNames) === 0) {
				continue;
			}

			$errors[] = RuleErrorBuilder::message(sprintf(
				'Trying to mock an undefined method %s() on class %s.',
				$method,
				implode('|', $classNames)
			))->identifier('phpunit.mockMethod')->build();
		}

		return $errors;
	}

}
