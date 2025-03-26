<?php

namespace Bug227;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class Foo
{

	public function addCacheTags(array $tags)
	{

	}

	public function getLanguage(): stdClass
	{

	}

}

class SomeTest extends TestCase
{

	protected MockObject|Foo $tsfe;

	protected function setUp(): void
	{
		$this->tsfe = $this->getMockBuilder(Foo::class)
			->onlyMethods(['addCacheTags', 'getLanguage'])
			->disableOriginalConstructor()
			->getMock();
		$this->tsfe->method('getLanguage')->willReturn('aaa');
	}

	public function testSometest(): void
	{
		$this->tsfe->expects(self::once())->method('addCacheTags');
	}
}
