<?php declare(strict_types = 1);

namespace Dummy;

class InvalidMethodCoversAnnotation extends \PHPUnit\Framework\TestCase
{
	/**
	 * @backupGlobals
	 * @backupGlobals enabled
	 * @backupGlobals disabled
	 */
	public function backupGlobals() {}

	/**
	 * @backupStaticAttributes
	 * @backupStaticAttributes enabled
	 * @backupStaticAttributes  disabled
	 */
	public function backupStaticAttributes() {}

	/**
	 * @covers\Dummy\Foo::assertSame
	 * @covers \Dummy\Foo::assertSame
	 * @covers::assertSame
	 * @covers ::assertSame
	 */
	public function covers() {}

	/**
	 * @coversDefaultClass\Dummy\Foo
	 * @coversDefaultClass \Dummy\Foo
	 */
	public function coversDefaultClass() {}

	/**
	 * @dataProvider
	 * @dataProvider foo
	 */
	public function dataProvider() {}

	/**
	 * @depends
	 * @depends foo
	 */
	public function depends() {}

	/**
	 * @preserveGlobalState
	 * @preserveGlobalState enabled
	 * @preserveGlobalState disabled
	 */
	public function preserveGlobalState() {}

	/**
	 * @requires
	 * @requires PHP >= 5.3
	 */
	public function requiresAnnotation() {}

	/**
	 * @testDox
	 * @testDox foo bar
	 */
	public function testDox() {}

	/**
	 * @testWith
	 * @testWith ['foo', 'bar']
	 */
	public function testWith() {}

	/**
	 * @ticket
	 * @ticket 1234
	 */
	public function ticket() {}

	/**
	 * @uses
	 * @uses foo
	 */
	public function uses() {}
}
