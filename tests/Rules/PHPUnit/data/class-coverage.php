<?php declare(strict_types = 1);

namespace ClassCoverage;

/**
 * @coversDefaultClass \Not\A\Class
 */
class CoversShouldExistTestCase extends \PHPUnit\Framework\TestCase
{
}

/**
 * @coversDefaultClass \PHPUnit\Framework\TestCase
 */
class CoversShouldExistTestCase2 extends \PHPUnit\Framework\TestCase
{
}

/**
 * @coversDefaultClass \PHPUnit\Framework\TestCase
 * @coversDefaultClass \PHPUnit\Framework\TestCase
 */
class MultipleCoversDefaultClass extends \PHPUnit\Framework\TestCase
{
}

/**
 * @covers \ClassCoverage\testable
 * @covers \Not\A\Class
 */
class CoversFunction extends \PHPUnit\Framework\TestCase
{
}

function testable(): void
{

}

/**
 * @covers
 */
class CoversNothing extends \PHPUnit\Framework\TestCase
{
}

/**
 * @covers NotFullyQualified
 */
class CoversNotFullyQualified extends \PHPUnit\Framework\TestCase
{
}

/**
 * @covers ::str_replace
 */
class CoversGlobalFunction extends \PHPUnit\Framework\TestCase
{
}
