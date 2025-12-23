<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Rules\StrictCalls\DynamicCallOnStaticMethodsRule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<DynamicCallOnStaticMethodsRule>
 */
class DynamicCallToAssertionIgnoreExtensionTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		/** @phpstan-ignore phpstanApi.classConstant */
		return self::getContainer()->getByType(DynamicCallOnStaticMethodsRule::class);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/dynamic-call-to-assertion.php'], [
			[
				'Dynamic call to static method DynamicCallToAssertion\Foo::staticFn().',
				17,
			],
		]);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/data/dynamic-call-to-assertion.neon',
		];
	}

}
