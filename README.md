# PHPStan PHPUnit extensions and rules

[![Build Status](https://travis-ci.org/phpstan/phpstan-phpunit.svg)](https://travis-ci.org/phpstan/phpstan-phpunit)
[![Latest Stable Version](https://poser.pugx.org/phpstan/phpstan-phpunit/v/stable)](https://packagist.org/packages/phpstan/phpstan-phpunit)
[![License](https://poser.pugx.org/phpstan/phpstan-phpunit/license)](https://packagist.org/packages/phpstan/phpstan-phpunit)

* [PHPStan](https://github.com/phpstan/phpstan)
* [PHPUnit](https://phpunit.de)

This extension provides following features:

* `createMock()`, `getMockForAbstractClass()` and `getMockFromWsdl()` methods return an intersection type (see the [detailed explanation of intersection types](https://medium.com/@ondrejmirtes/union-types-vs-intersection-types-fd44a8eacbb)) of the mock object and the mocked class so that both methods from the mock object (like `expects`) and from the mocked class are available on the object.
* `getMock()` called on `MockBuilder` is also supported.
* Interprets `Foo|PHPUnit_Framework_MockObject_MockObject` in phpDoc so that it results in an intersection type instead of a union type.
* Defines early terminating method calls for the `PHPUnit\Framework\TestCase` class to prevent undefined variable errors.

It also contains this framework-specific rule (can be enabled separately):

* Check that both values passed to `assertSame()` method are of the same type.

It also contains this strict framework-specific rules (can be enabled separately):

* Check that you are not using `assertSame()` with `true` as expected value. `assertTrue()` should be used instead.
* Check that you are not using `assertSame()` with `false` as expected value. `assertFalse()` should be used instead.
* Check that you are not using `assertSame()` with `null` as expected value. `assertNull()` should be used instead.

## How to document mock objects in phpDocs?

If you need to configure the mock even after you assign it to a property or return it from a method, you should add `PHPUnit_Framework_MockObject_MockObject` to the phpDoc:

```php
/**
 * @return Foo&PHPUnit_Framework_MockObject_MockObject
 */
private function createFooMock()
{
	return $this->createMock(Foo::class);
}

public function testSomething()
{
	$fooMock = $this->createFooMock();
	$fooMock->method('doFoo')->will($this->returnValue('test'));
	$fooMock->doFoo();
}
```

Please note that the correct syntax for intersection types is `Foo&PHPUnit_Framework_MockObject_MockObject`. `Foo|PHPUnit_Framework_MockObject_MockObject` is also supported, but only for ecosystem and legacy reasons.

If the mock is fully configured and only the methods of the mocked class are supposed to be called on the value, it's fine to typehint only the mocked class:

```php
/** @var Foo */
private $foo;

protected function setUp()
{
	$fooMock = $this->createMock(Foo::class);
	$fooMock->method('doFoo')->will($this->returnValue('test'));
	$this->foo = $foo;
}

public function testSomething()
{
	$this->foo->doFoo();
	// $this->foo->method() and expects() can no longer be called
}
```

## Usage

To use this extension, require it in [Composer](https://getcomposer.org/):

```bash
composer require --dev phpstan/phpstan-phpunit
```

And include extension.neon in your project's PHPStan config:

```
includes:
	- vendor/phpstan/phpstan-phpunit/extension.neon
```

To perform framework-specific checks, include also this file:

```
	- vendor/phpstan/phpstan-phpunit/rules.neon
```

To perform addition strict PHPUnit checks, include also this file:

```
	- vendor/phpstan/phpstan-phpunit/strictRules.neon
```
