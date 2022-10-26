<?php

namespace Bug141;

use PHPUnit\Framework\TestCase;

class Foo extends TestCase
{

	/**
	 * @param array<'0.6.0'|'1.0.0'|'1.0.x-dev'|'1.1.x-dev'|'9999999-dev'|'dev-feature-b', true> $a
	 */
	public function doFoo(array $a): void
	{
		$this->assertEmpty($a);
	}

	/**
	 * @param non-empty-array<'0.6.0'|'1.0.0'|'1.0.x-dev'|'1.1.x-dev'|'9999999-dev'|'dev-feature-b', true> $a
	 */
	public function doBar(array $a): void
	{
		$this->assertEmpty($a);
	}

	public function doBaz(): void
	{
		$expected = [
			'0.6.0' => true,
			'1.0.0' => true,
			'1.0.x-dev' => true,
			'1.1.x-dev' => true,
			'dev-feature-b' => true,
			'dev-feature/a-1.0-B' => true,
			'dev-master' => true,
			'9999999-dev' => true, // alias of dev-master
		];

		/** @var array<string> */
		$packages = ['0.6.0', '1.0.0', '1'];

		foreach ($packages as $version) {
			if (isset($expected[$version])) {
				unset($expected[$version]);
			} else {
				throw new \Exception('Unexpected version '.$version);
			}
		}

		$this->assertEmpty($expected);
	}

}
