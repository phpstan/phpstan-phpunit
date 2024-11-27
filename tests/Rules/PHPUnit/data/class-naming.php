<?php declare(strict_types = 1);

namespace ExampleTestCase;

final class IncorrectlyNamedTst extends \PHPUnit\Framework\TestCase
{

}

abstract class IncorrectlyNamedTestCse extends \PHPUnit\Framework\TestCase
{

}

class NotFinalTest extends \PHPUnit\Framework\TestCase
{

}

class NotFinalOrNamedCorrectly extends \PHPUnit\Framework\TestCase
{

}

new class() extends \PHPUnit\Framework\TestCase {

};

final class CorrectlyNamedAndFinalTest extends \PHPUnit\Framework\TestCase
{

}

abstract class CorrectlyNamedTestCase extends \PHPUnit\Framework\TestCase
{

}
