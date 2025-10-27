<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PHPStan\Rules\Methods\MissingMethodReturnTypehintRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<MissingMethodReturnTypehintRule>
 */
class DataProviderReturnTypeIgnoreExtensionTest extends RuleTestCase {
	protected function getRule(): Rule
	{
		/** @phpstan-ignore phpstanApi.classConstant */
		$rule = self::getContainer()->getByType(MissingMethodReturnTypehintRule::class);
		return $rule;
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/data-provider-iterable-value.php'], [
		]);
	}

	static public function getAdditionalConfigFiles(): array
	{
		return [__DIR__ . '/../../../extension.neon'];
	}
}
