<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;

class GetMockBuilderDynamicReturnTypeExtension implements \PHPStan\Type\DynamicMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return \PHPUnit\Framework\TestCase::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === 'getMockBuilder';
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		$mockBuilderType = $methodReflection->getReturnType();
		if (count($methodCall->args) === 0) {
			return $mockBuilderType;
		}
		$arg = $methodCall->args[0]->value;
		if (!($arg instanceof \PhpParser\Node\Expr\ClassConstFetch)) {
			return $mockBuilderType;
		}

		$class = $arg->class;
		if (!($class instanceof \PhpParser\Node\Name)) {
			return $mockBuilderType;
		}

		$class = (string) $class;
		if ($class === 'static') {
			return $mockBuilderType;
		}

		if ($class === 'self') {
			$class = $scope->getClassReflection()->getName();
		}

		if (!$mockBuilderType instanceof TypeWithClassName) {
			throw new \PHPStan\ShouldNotHappenException();
		}

		return new MockBuilderType($mockBuilderType, $class);
	}

}
