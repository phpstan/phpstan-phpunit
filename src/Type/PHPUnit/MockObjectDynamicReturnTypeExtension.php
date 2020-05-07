<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPUnit\Framework\MockObject\InvocationMocker;
use PHPUnit\Framework\MockObject\MockObject;

class MockObjectDynamicReturnTypeExtension implements \PHPStan\Type\DynamicMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return MockObject::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === 'expects';
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		$type = $scope->getType($methodCall->var);
		if (!($type instanceof IntersectionType)) {
			return new GenericObjectType(InvocationMocker::class, []);
		}

		$mockClasses = array_filter($type->getTypes(), function (Type $type): bool {
			return !$type instanceof TypeWithClassName || $type->getClassName() !== MockObject::class;
		});

		return new GenericObjectType(InvocationMocker::class, $mockClasses);
	}

}
