<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeWithClassName;

class MockBuilderDynamicReturnTypeExtension implements \PHPStan\Type\DynamicMethodReturnTypeExtension, \PHPStan\Reflection\BrokerAwareExtension
{

	/**
	 * @var \PHPStan\Broker\Broker
	 */
	private $broker;

	public function setBroker(Broker $broker)
	{
		$this->broker = $broker;
	}

	public function getClass(): string
	{
		$testCase = $this->broker->getClass(\PHPUnit\Framework\TestCase::class);
		$mockBuilderType = $testCase->getNativeMethod('getMockBuilder')->getReturnType();
		if (!$mockBuilderType instanceof TypeWithClassName) {
			throw new \PHPStan\ShouldNotHappenException();
		}

		return $mockBuilderType->getClassName();
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

		return TypeCombinator::intersect(
			new ObjectType($calledOnType->getMockedClass()),
			$methodReflection->getReturnType()
		);
	}

}
