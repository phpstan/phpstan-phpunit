<?php declare(strict_types = 1);

namespace AssertSameNullTestCaseFix;

class AssertSameNullExpectedTestCase extends \PHPUnit\Framework\TestCase
{
	/**
	 * @return null
	 */
	public function returnNull()
	{
		return null;
	}

	public function doFoo(): void
	{
		$this->assertSame(null, 'a');

		\PHPUnit\Framework\Assert::assertSame($this->returnNull(), 'foo');
	}

}
