# PHPStan PHPUnit extensions and rules

[![Build Status](https://travis-ci.org/phpstan/phpstan-phpunit.svg)](https://travis-ci.org/phpstan/phpstan-phpunit)
[![Latest Stable Version](https://poser.pugx.org/phpstan/phpstan-phpunit/v/stable)](https://packagist.org/packages/phpstan/phpstan-phpunit)
[![License](https://poser.pugx.org/phpstan/phpstan-phpunit/license)](https://packagist.org/packages/phpstan/phpstan-phpunit)

* [PHPStan](https://github.com/phpstan/phpstan)
* [PHPUnit](https://phpunit.de)

This extension provides following features:

* `createMock()` method returns an intersection type of the mock object and the mocked class so that both methods from the mock object (like `expects`) and from the mocked class are available on the object.
* Interprets `Foo|PHPUnit_Framework_MockObject_MockObject` in phpDoc so that it results in an intersection type instead of a union type.
* Defines early terminating method calls for the `PHPUnit\Framework\TestCase` class to prevent undefined variable errors.

It also contains this framework-specific rule (can be enabled separately):

* Check that both values passed to `assertSame()` method are of the same type.

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
