<?php

namespace DataProviderIterableValueTest;

use ArrayObject;
use Generator;
use Iterator;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;
use Traversable;

class Foo extends TestCase {
	/**
	 * @dataProvider dataProvider
	 * @dataProvider dataProvider2
	 * @dataProvider dataProvider3
	 * @dataProvider dataProvider4
	 * @dataProvider dataProvider5
	 * @dataProvider dataProvider6
	 */
	public function testFoo():void {

	}

	public function dataProvider(): iterable {
		return [
			[1, 2],
			[3, 4],
			[5, 6],
		];
	}

	public function dataProvider2(): iterable {
		$i = rand(0, 10);

		return [
			[$i, 2],
		];
	}

	public function notADataProvider(): iterable {
		return [
			[1, 2],
			[3, 4],
			[5, 6],
		];
	}

	public function dataProvider3(): Iterator {
		$i = rand(0, 10);

		yield [$i, 2];
	}

	public function dataProvider4(): IteratorAggregate {
		$i = rand(0, 10);

		return new ArrayObject([
			[$i, 2],
		]);
	}

	public function dataProvider5(): Generator {
		$i = rand(0, 10);

		yield [$i, 2];
	}

	public function dataProvider6(): Traversable {
		$i = rand(0, 10);

		yield [$i, 2];
	}
}
