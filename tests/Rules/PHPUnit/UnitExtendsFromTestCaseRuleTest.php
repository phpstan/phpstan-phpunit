<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

/**
 * @extends \PHPStan\Testing\RuleTestCase<UnitExtendsFromTestCaseRule>
 */
class UnitExtendsFromTestCaseRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): \PHPStan\Rules\Rule
	{
		return new UnitExtendsFromTestCaseRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/unit-extends/unit-extends-exception.php'], []);
		$this->analyse([__DIR__ . '/data/unit-extends/unit-extends-no-namespace.php'], []);
		$this->analyse([__DIR__ . '/data/unit-extends/unit-extends-functional-test.php'], []);
		$this->analyse([__DIR__ . '/data/unit-extends/unit-extends-ok-test.php'], []);
		$this->analyse([__DIR__ . '/data/unit-extends/unit-extends-not-extending-test.php'], [
			[
				'You should only extend from one of the following classes in unit tests: "PHPUnit\Framework\TestCase, PHPStan\Testing\RuleTestCase".',
				05,
			],
		]);
		$this->analyse([__DIR__ . '/data/unit-extends/unit-extends-invalid-test.php'], [
			[
				'You should only extend from one of the following classes in unit tests: "PHPUnit\Framework\TestCase, PHPStan\Testing\RuleTestCase".',
				05,
			],
		]);
	}

}
