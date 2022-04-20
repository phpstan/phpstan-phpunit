<?php declare(strict_types = 1);

namespace ExampleTestCase;

class AssertSameNullExpectedTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertSameWithNullAsExpected()
	{
		$this->assertSame(null, 'a');

		$a = null;
		$this->assertSame($a, 'b');

		$this->assertSame('a', 'b'); // OK

		/** @var string|null $c */
		$c = null;
		$this->assertSame($c, 'foo'); // nullable is OK
	}

	public function testAssertSameIsDetectedWithDirectAssertAccess()
	{
		\PHPUnit\Framework\Assert::assertSame(null, 'foo');
	}

	public function testConstant(): void
	{
		\PHPUnit\Framework\Assert::assertSame(PHPSTAN_PHPUNIT_NULL, 'foo');
	}

	private const NULL = null;

	public function testClassConstant(): void
	{
		\PHPUnit\Framework\Assert::assertSame(self::NULL, 'foo');
	}

	public function returnNullable(): ?string
	{

	}

	/**
	 * @return null
	 */
	public function returnNull()
	{
		return null;
	}

	public function testMethodCalls(): void
	{
		\PHPUnit\Framework\Assert::assertSame($this->returnNull(), 'foo');
		\PHPUnit\Framework\Assert::assertSame($this->returnNullable(), 'foo');
	}

}

const PHPSTAN_PHPUNIT_NULL = null;
