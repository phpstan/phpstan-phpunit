<?php declare(strict_types = 1);

namespace ExampleTestCase;

/**
 * @coversDefaultClass \Not\A\Class
 */
class CoversShouldExistTestCase extends \PHPUnit\Framework\TestCase
{

	/**
	 * @covers ::ignoreThis
	 */
	public function testFunctionCoversBroken()
	{
	}

	/**
	 * @covers \PHPUnit\Framework\TestCase::assertEquals
	 */
	public function testFunctionCoversGoodFQDN()
	{
	}

	/**
	 * @covers \PHPUnit\Framework\TestCase::assertNotReal
	 */
	public function testFunctionCoversBadFQDN()
	{
	}

	/**
	 * @covers \Not\A\Class::foo
	 */
	public function testFunctionCoversBadFQDN2()
	{
	}

}

/**
 * @coversDefaultClass \PHPUnit\Framework\TestCase
 */
class CoversShouldExistTestCase2 extends \PHPUnit\Framework\TestCase
{

	/**
	 * @coversDefaultClass \ExampleTestCase\CoversShouldExistTestCase
	 */
	public function testBadCoversDefault() {}

	/**
	 * @covers ::assertEquals
	 */
	public function testFunctionCoversRealFunction()
	{
	}

	/**
	 * @covers ::assertNotReal
	 */
	public function testFunctionCoversBroken()
	{
	}

}

/**
 * @coversDefaultClass \PHPUnit\Framework\TestCase
 * @coversDefaultClass \PHPUnit\Framework\TestCase
 */
class MultipleCoversDefaultClass extends \PHPUnit\Framework\TestCase
{


}
