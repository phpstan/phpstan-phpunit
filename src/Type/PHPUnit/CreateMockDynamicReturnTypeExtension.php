<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

class CreateMockDynamicReturnTypeExtension implements \PHPStan\Type\DynamicMethodReturnTypeExtension
{

	/** @var int[] */
	private $methods = [
		'createMock' => 0,
		'createConfiguredMock' => 0,
		'createPartialMock' => 0,
		'createTestProxy' => 0,
		'getMockForAbstractClass' => 0,
		'getMockFromWsdl' => 1,
	];

	public function getClass(): string
	{
		return \PHPUnit\Framework\TestCase::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return array_key_exists($methodReflection->getName(), $this->methods);
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		$argumentIndex = $this->methods[$methodReflection->getName()];
		if (!isset($methodCall->args[$argumentIndex])) {
			return $methodReflection->getReturnType();
		}
		$arg = $methodCall->args[$argumentIndex]->value;
		if (!($arg instanceof \PhpParser\Node\Expr\ClassConstFetch)) {
			return $methodReflection->getReturnType();
		}

		$class = $arg->class;
		if (!($class instanceof \PhpParser\Node\Name)) {
			return $methodReflection->getReturnType();
		}

		$class = (string) $class;

		if ($class === 'static') {
			return $methodReflection->getReturnType();
		}

		if ($class === 'self') {
			$class = $scope->getClassReflection()->getName();
		}

		return TypeCombinator::intersect(
			new ObjectType($class),
			$methodReflection->getReturnType()
		);
	}

}
