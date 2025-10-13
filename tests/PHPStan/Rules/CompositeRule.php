<?php declare(strict_types = 1);

namespace PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use function get_class;

class CompositeRule implements Rule
{

	private DirectRegistry $registry;

	public function __construct(DirectRegistry $ruleRegisty)
	{
		$this->registry = $ruleRegisty;
	}


	public function getNodeType(): string
	{
		return Node::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		$errors = [];

		$nodeType = get_class($node);
		foreach ($this->registry->getRules($nodeType) as $rule) {
			foreach ($rule->processNode($node, $scope) as $error) {
				$errors[] = $error;
			}
		}

		return $errors;
	}

}
