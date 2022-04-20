<?php declare(strict_types = 1);

namespace ExampleTestCase;

class AssertSameBooleanExpectedTestCase extends \PHPUnit\Framework\TestCase
{

	public function testAssertSameWithBooleanAsExpected()
	{
		$this->assertSame(true, 'a');
		$this->assertSame(false, 'a');

		$truish = true;
		$this->assertSame($truish, true); // using variable is OK

		$falsish = false;
		$this->assertSame($falsish, false); // using variable is OK

		/** @var bool $a */
		$a = null;
		$this->assertSame($a, 'b'); // OK
	}

	public function testAssertSameIsDetectedWithDirectAssertAccess()
	{
		\PHPUnit\Framework\Assert::assertSame(true, 'foo');
	}

	public function testConstants(): void
	{
		\PHPUnit\Framework\Assert::assertSame(PHPSTAN_PHPUNIT_TRUE, 'foo');
		\PHPUnit\Framework\Assert::assertSame(PHPSTAN_PHPUNIT_FALSE, 'foo');
	}

	private const TRUE = true;
	private const FALSE = false;

	public function testClassConstants(): void
	{
		\PHPUnit\Framework\Assert::assertSame(self::TRUE, 'foo');
		\PHPUnit\Framework\Assert::assertSame(self::FALSE, 'foo');
	}

	public function returnBool(): bool
	{
		return true;
	}

	/**
	 * @return true
	 */
	public function returnTrue(): bool
	{
		return true;
	}

	/**
	 * @return false
	 */
	public function returnFalse(): bool
	{
		return false;
	}

	public function testMethodCalls(): void
	{
		\PHPUnit\Framework\Assert::assertSame($this->returnTrue(), 'foo');
		\PHPUnit\Framework\Assert::assertSame($this->returnFalse(), 'foo');
		\PHPUnit\Framework\Assert::assertSame($this->returnBool(), 'foo');
	}

	public function testNonLowercase(): void
	{
		\PHPUnit\Framework\Assert::assertSame(True, 'foo');
		\PHPUnit\Framework\Assert::assertSame(False, 'foo');
	}

}

const PHPSTAN_PHPUNIT_TRUE = true;
const PHPSTAN_PHPUNIT_FALSE = false;
