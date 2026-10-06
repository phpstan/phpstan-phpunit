<?php declare(strict_types = 1);

namespace AssertEmptyIsDiscouragedFixTest;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class UserDefinedObject
{

}

/** @final */
class PhpDocFinalObject
{

}

class UserDefinedCountable implements \Countable
{

	public function count(): int
	{
		return 0;
	}

}

class OpenParent
{

}

interface Thing
{

}

class ThingImplementation implements Thing, \Countable
{

	public function count(): int
	{
		return 0;
	}

}

final class FinalEmptyIterator extends \EmptyIterator
{

}

final class FinalCountable implements \Countable
{

	public function count(): int
	{
		return 0;
	}

}

final class AssertEmptyTest extends TestCase
{

	/**
	 * @param non-falsy-string $nonFalsyString
	 * @param non-falsy-string $otherNonFalsyString
	 * @param int $phpDocInteger
	 * @param string|int $union
	 */
	public function test(
		array $arguments,
		bool $boolean,
		array $array,
		int $integer,
		float $float,
		string $string,
		$union,
		string $nonFalsyString,
		string $otherNonFalsyString,
		?\stdClass $nullableObject,
		?object $otherNullableObject,
		?\SimpleXMLElement $nullableSimpleXml,
		?UserDefinedObject $nullableUserDefinedObject,
		?UserDefinedObject $otherNullableUserDefinedObject,
		?PhpDocFinalObject $nullablePhpDocFinalObject,
		?UserDefinedCountable $nullableCountable,
		?OpenParent $nullableOpenParent,
		?Thing $nullableThing,
		?FinalEmptyIterator $nullableEmptyIterator,
		?FinalCountable $nullableFinalCountable,
		$phpDocInteger,
		$mixed
	): void
	{
		$this->assertEmpty(...$arguments);
		$this->assertEmpty($boolean);
		$this->assertNotEmpty($boolean);
		$this->assertEmpty($array, 'message');
		$this->assertNotEmpty($array);
		$this->assertEmpty($integer);
		$this->assertNotEmpty($integer);
		Assert::assertEmpty($float);
		Assert::assertNotEmpty($float);
		$this->assertEmpty($nonFalsyString);
		$this->assertNotEmpty($otherNonFalsyString);
		$this->assertEmpty($string);
		$this->assertNotEmpty($string);
		$this->assertEmpty($union);
		$this->assertNotEmpty($union);
		$this->assertEmpty($nullableObject);
		$this->assertNotEmpty($otherNullableObject);
		$this->assertEmpty($nullableSimpleXml);
		$this->assertEmpty($nullableUserDefinedObject);
		$this->assertNotEmpty($otherNullableUserDefinedObject);
		$this->assertEmpty($nullablePhpDocFinalObject);
		$this->assertNotEmpty($nullablePhpDocFinalObject);
		$this->assertEmpty($nullableCountable);
		$this->assertNotEmpty($nullableCountable);
		$this->assertEmpty($nullableOpenParent);
		$this->assertNotEmpty($nullableOpenParent);
		$this->assertEmpty($nullableThing);
		$this->assertNotEmpty($nullableThing);
		$this->assertEmpty($nullableEmptyIterator);
		$this->assertNotEmpty($nullableEmptyIterator);
		$this->assertEmpty($nullableFinalCountable);
		$this->assertNotEmpty($nullableFinalCountable);
		$this->assertNotEmpty($phpDocInteger);
		$this->assertEmpty($mixed);
	}

	public function testNativeNonFalsyString(string $value): void
	{
		if ($value === '' || $value === '0') {
			return;
		}

		$this->assertNotEmpty($value);
	}

}
