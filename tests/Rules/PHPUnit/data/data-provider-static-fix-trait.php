<?php declare(strict_types=1);

namespace DataProviderStaticFixTrait;

trait ProvidesData
{

	public function provide(): iterable
	{
		yield [];
	}

}

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
