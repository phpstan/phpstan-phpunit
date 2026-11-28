<?php declare(strict_types=1);

namespace DataProviderStaticFixTraitOtherFile;

class FooTest extends \PHPUnit\Framework\TestCase
{

	use ProvidesData;

	/**
	 * @dataProvider provide
	 */
	public function testFoo(): void
	{
	}

}
