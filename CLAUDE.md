# CLAUDE.md

This file provides guidance for AI assistants working with the phpstan/phpstan-phpunit repository.

## Project Overview

This is a PHPStan extension that provides advanced static analysis support for PHPUnit test suites. It offers:

- **Type extensions**: Correct return types for `createMock()`, `getMockForAbstractClass()`, `getMockFromWsdl()`, `MockBuilder::getMock()`, etc., returning intersection types (e.g., `MockObject&Foo`) so both mock and original class methods are available.
- **PHPDoc interpretation**: Converts `Foo|MockObject` union types in phpDocs to intersection types.
- **Assert type narrowing**: Specifies types of expressions passed to `assertInstanceOf`, `assertTrue`, `assertInternalType`, etc.
- **Early terminating methods**: Defines `fail()`, `markTestIncomplete()`, `markTestSkipped()` as early terminating to prevent false positive undefined variable errors.
- **Strict rules** (in `rules.neon`): Checks for better assertion usage (e.g., prefer `assertTrue()` over `assertSame(true, ...)`), `@covers` validation, data provider declaration checks, and more.

## PHP Version Requirements

This repository supports **PHP 7.4+**. Do not use language features unavailable in PHP 7.4 (e.g., enums, fibers, readonly properties, intersection types in code — though they appear in stubs/phpDocs).

## PHPUnit Compatibility

The extension supports multiple PHPUnit versions: **^9.5, ^10.5, ^11.5, ^12.0**. Code must be compatible across all these versions. The CI matrix tests all combinations.

## Common Commands

```bash
# Install dependencies
composer install

# Run all checks (lint, coding standard, tests, PHPStan)
make check

# Run tests only
make tests

# Run PHPStan analysis
make phpstan

# Run linting
make lint

# Install coding standard tool (first time only)
make cs-install

# Run coding standard checks
make cs

# Fix coding standard issues
make cs-fix

# Generate PHPStan baseline
make phpstan-generate-baseline
```

## Project Structure

```
src/
├── PhpDoc/PHPUnit/          # PHPDoc type resolution extensions
├── Rules/PHPUnit/           # Static analysis rules for PHPUnit
└── Type/PHPUnit/            # Type-specifying and dynamic return type extensions
    └── Assert/              # Assert method type narrowing

tests/
├── Rules/PHPUnit/           # Rule tests and test data (data/ subdirectory)
├── Rules/Methods/           # Method call rule tests
├── Type/PHPUnit/            # Type extension tests and test data (data/ subdirectory)
└── bootstrap.php            # Test bootstrap (loads Composer autoloader)

stubs/                       # PHPUnit stub files for type definitions
```

## Configuration Files

- **`extension.neon`** — Main extension configuration registered via phpstan/extension-installer. Defines parameters, services (type extensions, helpers), and stub files.
- **`rules.neon`** — Strict PHPUnit-specific rules. Loaded separately; users opt in by including this file.
- **`phpstan.neon`** — Self-analysis configuration (level 8, with strict rules and deprecation rules).
- **`phpstan-baseline.neon`** — Baseline for known PHPStan errors in the project itself.
- **`phpunit.xml`** — PHPUnit configuration for running the test suite.

## Architecture

### Type Extensions (`src/Type/PHPUnit/`)

These implement PHPStan interfaces to provide correct types:

- `MockBuilderDynamicReturnTypeExtension` — Preserves `MockBuilder<T>` generic type through chained method calls.
- `MockForIntersectionDynamicReturnTypeExtension` — Returns `MockObject&T` intersection types for mock creation methods.
- `Assert/AssertMethodTypeSpecifyingExtension` (and function/static variants) — Narrows types after assert calls (e.g., after `assertInstanceOf(Foo::class, $x)`, `$x` is known to be `Foo`).

### Rules (`src/Rules/PHPUnit/`)

These implement `PHPStan\Rules\Rule<T>` to report errors:

- `AssertSameBooleanExpectedRule`, `AssertSameNullExpectedRule` — Suggest specific assertions over generic `assertSame`.
- `AssertSameWithCountRule` — Suggest `assertCount()` over `assertSame(count(...), ...)`.
- `ClassCoversExistsRule`, `ClassMethodCoversExistsRule` — Validate `@covers` annotations reference existing code.
- `DataProviderDeclarationRule`, `DataProviderDataRule` — Validate data provider declarations and data.
- `MockMethodCallRule` — Check mock method calls are valid.
- `ShouldCallParentMethodsRule` — Verify `setUp()`/`tearDown()` call parent methods.

### Stubs (`stubs/`)

PHPStan stub files that provide generic type information for PHPUnit classes (e.g., `TestCase::createMock<T>()` returns `MockObject&T`).

## Writing Tests

- **Rule tests** extend `PHPStan\Testing\RuleTestCase<T>`. They implement `getRule()` and call `$this->analyse()` with a test data file path and expected errors array. Test data files live in `tests/Rules/PHPUnit/data/`.
- **Type tests** extend `PHPStan\Testing\TypeInferenceTestCase`. They use `@dataProvider` with `self::gatherAssertTypes()` or `self::dataFileAsserts()` and call `$this->assertFileAsserts()`. Test data files live in `tests/Type/PHPUnit/data/`.
- Both types override `getAdditionalConfigFiles()` to return the path to `extension.neon` (and sometimes `rules.neon`).

## Coding Standards

- Uses tabs for indentation (PHP, XML, NEON files).
- Uses spaces for YAML files (indent size 2).
- Coding standard is enforced via [phpstan/build-cs](https://github.com/phpstan/build-cs) (PHPCS with a custom standard).
- Run `make cs` to check, `make cs-fix` to auto-fix.

## CI Pipeline

The GitHub Actions workflow (`.github/workflows/build.yml`) runs on the `2.0.x` branch and pull requests:

1. **Lint** — PHP syntax check across PHP 7.4–8.5.
2. **Coding Standard** — PHPCS checks using build-cs.
3. **Tests** — PHPUnit across PHP 7.4–8.5 × lowest/highest dependencies × PHPUnit 9.5/10.5/11.5/12.0 (with version-appropriate exclusions).
4. **Static Analysis** — PHPStan self-analysis with the same matrix.
5. **Mutation Testing** — Infection framework on PHP 8.2–8.5, requires 100% MSI on changed lines.

## Development Branch

The main development branch is `2.0.x`.
