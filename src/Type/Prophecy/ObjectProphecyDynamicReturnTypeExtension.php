<?php declare(strict_types = 1);

namespace PHPStan\Type\Prophecy;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Type;

class ObjectProphecyDynamicReturnTypeExtension implements \PHPStan\Type\DynamicMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return \PHPUnit_Framework_MockObject_MockBuilder::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return true;
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		if ($methodReflection->getName() === 'reveal') {
			return $scope->getType($methodCall->var);
		}

		return $methodReflection->getReturnType();
	}

}
