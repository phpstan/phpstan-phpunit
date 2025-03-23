<?php

namespace Rules\data;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class Foo extends TestCase
{

	public function doFoo(MockObject $mockService): void
	{
		$mockService
			->expects($this->exactly(1))
			->method('get')
			->with(24)
			->willReturn('24');

		$mockService
			->method('get')
			->with(24)
			->willReturn('24');

		$mockService
			->expects($this->exactly(1))
			->method('get')
			->willReturn('24');

		$mockService
			->method('get')
			->willReturn('24');
	}

}
