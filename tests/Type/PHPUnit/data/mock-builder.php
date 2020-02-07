<?php

use PHPUnit\Framework\TestCase;

$test = new class () extends TestCase {};

$simpleInterface = $test->getMockBuilder(\ExampleTestCase\FooInterface::class)->getMock();
$doubleInterface = $test->getMockBuilder([\ExampleTestCase\FooInterface::class, \ExampleTestCase\BarInterface::class])->getMock();

die;
