<?php declare(strict_types = 1);

namespace PHPStan\Rules;

use PHPStan\Rules\Methods\CallMethodsRule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<CallMethodsRule>
 */
class CallMethodsRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return self::getContainer()->getByType(CallMethodsRule::class);
	}

	public function testBug222(): void
	{
		$this->analyse([__DIR__ . '/data/bug-222.php'], []);
	}

	/**
	 * @return string[]
	 */
	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/../../extension.neon',
		];
	}

}
