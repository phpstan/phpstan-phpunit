<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

class MockBuilderDynamicReturnTypeExtension implements \PHPStan\Type\DynamicMethodReturnTypeExtension
{

	public static function getClass(): string
	{
		return \PHPUnit_Framework_MockObject_MockBuilder::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return true;
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		$calledOnType = $scope->getType($methodCall->var);
		if (!in_array(
			$methodReflection->getName(),
			[
				'getMock',
				'getMockForAbstractClass',
			],
			true
		)) {
			return $calledOnType;
		}

		if (!$calledOnType instanceof MockBuilderType) {
			return $methodReflection->getReturnType();
		}

		$mockedClassType = new ObjectType($calledOnType->getMockedClass());
		$mockType = new ObjectType(\PHPUnit_Framework_MockObject_MockObject::class);

		return TypeCombinator::intersect($mockedClassType, $mockType);
	}

}
