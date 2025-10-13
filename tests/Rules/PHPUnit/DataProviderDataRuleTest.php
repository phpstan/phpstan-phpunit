<?php declare(strict_types=1);

namespace Rules\PHPUnit;

use PHPStan\Rules\PHPUnit\DataProviderDataRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<DataProviderDataRule>
 */
class DataProviderDataRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		$reflection = $this->createReflectionProvider();

		return new DataProviderDataRule(
			$reflection
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/data-provider-data.php'], [
			[
				'Parameter #2 $input of method test::testTrim() expects string, int given.',
				25,
			],
			[
				'Parameter #2 $input of method test::testTrim() expects string, false given.',
				29,
			],
		]);
	}

	/**
	 * @return string[]
	 */
	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/data-provider-data.neon',
		];
	}
}
