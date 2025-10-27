<?php

namespace DataProviderIterableValueTest;

use PHPUnit\Framework\TestCase;

class Foo extends TestCase {
	/**
	 * @dataProvider dataProvider
	 * @dataProvider dataProvider2
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
}
