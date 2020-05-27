<?php

use PHPUnit\Framework\TestCase;

$test = new class () extends TestCase {};

$reflection = new ReflectionObject($test);
$reflection->getMethod('createMock')->setAccessible(true);
$simpleInterface = $test->createMock(\ExampleTestCase\FooInterface::class);
$doubleInterface = $test->createMock([\ExampleTestCase\FooInterface::class, \ExampleTestCase\BarInterface::class]);

die;
