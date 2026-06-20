<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\TestCase;

/**
 * @implements Rule<InClassNode>
 */
class ClassAttributeRequiresPhpVersionRule implements Rule
{

	private AttributeVersionRequirementHelper $attributeVersionRequirementHelper;

	public function __construct(
		AttributeVersionRequirementHelper $attributeVersionRequirementHelper
	)
	{
		$this->attributeVersionRequirementHelper = $attributeVersionRequirementHelper;
	}

	public function getNodeType(): string
	{
		return InClassNode::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		$classReflection = $scope->getClassReflection();
		if ($classReflection === null || $classReflection->is(TestCase::class) === false) {
			return [];
		}

		return $this->attributeVersionRequirementHelper->checkRequiresPhpVersion(
			$classReflection->getNativeReflection()->getBetterReflection()->getAttributesByName('PHPUnit\Framework\Attributes\RequiresPhp'),
			$scope,
		);
	}

}
