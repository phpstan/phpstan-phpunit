<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<CoversShouldExistRule>
 * @covers \PHPStan\Rules\PHPUnit\CoversShouldExistRule::processCoversDefault
 */
class CoversShouldExistRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		$broker = $this->createReflectionProvider();
		return new CoversShouldExistRule(
			self::getContainer()->getByType(Lexer::class),
			self::getContainer()->getByType(PhpDocParser::class),
			$broker
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/coverage.php'], [
			[
				'@coversDefaultClass does not provide a known class \Not\A\Class.',
				8,
			],
			[
				'@covers value ::ignoreThis references an unknown class.',
				14,
			],
			[
				'@covers value \PHPUnit\Framework\TestCase::assertNotReal references an unknown method.',
				28,
			],
			[
				'@covers value \Not\A\Class::foo references an unknown class.',
				35,
			],
			[
				'@coversDefaultClass found on class method testBadCoversDefault.',
				50,
			],
			[
				'@covers value ::assertNotReal references an unknown method.',
				62,
			],
		]);
	}

	/**
	 * @return string[]
	 */
	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/../../../extension.neon',
		];
	}

}
