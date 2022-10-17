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
