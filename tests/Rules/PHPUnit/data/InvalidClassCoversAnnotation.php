<?php declare(strict_types = 1);

namespace Dummy;

/**
 * @backupGlobals
 * @backupGlobals enabled
 * @backupGlobals disabled
 * @backupStaticAttributes
 * @backupStaticAttributes enabled
 * @backupStaticAttributes  disabled
 * @covers\Dummy\Foo::assertSame
 * @covers \Dummy\Foo::assertSame
 * @covers::assertSame
 * @covers ::assertSame
 * @coversDefaultClass\Dummy\Foo
 * @coversDefaultClass \Dummy\Foo
 * @dataProvider
 * @dataProvider foo
 * @depends
 * @depends foo
 * @preserveGlobalState
 * @preserveGlobalState enabled
 * @preserveGlobalState disabled
 * @requires
 * @requires PHP >= 5.3
 * @testDox
 * @testDox foo bar
 * @testWith
 * @testWith ['foo', 'bar']
 * @ticket
 * @ticket 1234
 * @uses
 * @uses foo
 */
class InvalidClassCoversAnnotation extends \PHPUnit\Framework\TestCase
{
}
