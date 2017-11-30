<?php declare(strict_types = 1);

namespace PHPStan\Type\Prophecy;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Type;
use Prophecy\Prophecy\ObjectProphecy;

class ObjectProphecyDynamicReturnTypeExtension implements \PHPStan\Type\DynamicMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return ObjectProphecy::class;
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
