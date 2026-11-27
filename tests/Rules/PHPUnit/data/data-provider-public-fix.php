<?php declare(strict_types=1);

namespace DataProviderPublicFix;

class FixTestCase extends \PHPUnit\Framework\TestCase
{

	private static function dataPrivate(): iterable
	{
		yield [];
	}

	protected static function dataProtected(): iterable
	{
		yield [];
	}

	protected function dataProtectedNonStatic(): iterable
	{
		yield [];
	}

	/**
	 * @dataProvider dataPrivate
	 * @dataProvider dataProtected
	 * @dataProvider dataProtectedNonStatic
	 */
	public function testFoo(): void
	{

	}

}
