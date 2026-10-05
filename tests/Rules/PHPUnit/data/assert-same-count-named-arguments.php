<?php // lint >= 8.0

namespace ExampleTestCaseFixNamedArguments;

use function count;

class AssertSameWithCountTestCase extends \PHPUnit\Framework\TestCase
{

	public function skipNamedArguments(Bar $bar): void
	{
		$this->assertSame(expected: 5, actual: count([1, 2, 3]), message: 'message');
		$this->assertSame(message: 'message', actual: count(value: [1, 2, 3]), expected: 5);
		self::assertSame(actual: $bar->count(), expected: 5);
		$this->assertSame(5, actual: count([1, 2, 3]), message: 'message');

		$this->assertSame(5, count(value: [1, 2, 3]), 'message');
	}

}

class Bar implements \Countable
{

	public function count(): int
	{
		return 1;
	}

}
