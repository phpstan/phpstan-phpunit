<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
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
		return 'PHPUnit\Framework\TestCase';
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		$name = $methodReflection->getName();
		return array_key_exists($methodReflection->getName(), $this->methods);
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		$argumentIndex = $this->methods[$methodReflection->getName()];
		$parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());
		if (!isset($methodCall->args[$argumentIndex])) {
			return $parametersAcceptor->getReturnType();
		}
		$argType = $scope->getType($methodCall->args[$argumentIndex]->value);

		$types = [];
		if ($argType instanceof ConstantStringType) {
			$types[] = new ObjectType($argType->getValue());
		}

		if ($argType instanceof ConstantArrayType) {
			$types = array_map(function (Type $argType): ObjectType {
				return new ObjectType($argType->getValue());
			}, $argType->getValueTypes());
		}

		if (count($types) === 0) {
			return $parametersAcceptor->getReturnType();
		}

		return TypeCombinator::intersect(
			$parametersAcceptor->getReturnType(),
			...$types
		);
	}

}
