<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;

class GetMockBuilderDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return 'PHPUnit\Framework\TestCase';
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === 'getMockBuilder';
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		$parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());
		$mockBuilderType = $parametersAcceptor->getReturnType();
		if (count($methodCall->args) === 0) {
			return $mockBuilderType;
		}
		if (!$mockBuilderType instanceof TypeWithClassName) {
			throw new \PHPStan\ShouldNotHappenException();
		}

		$argType = $scope->getType($methodCall->args[0]->value);
		if ($argType instanceof ConstantStringType) {
			$class = $argType->getValue();

			return new MockBuilderType($mockBuilderType, $class);
		}

		if ($argType instanceof ConstantArrayType) {
			$classes = array_map(function (ConstantStringType $argType): string {
				return $argType->getValue();
			}, $argType->getValueTypes());

			return new MockBuilderType($mockBuilderType, ...$classes);
		}

		return $mockBuilderType;
	}

}
