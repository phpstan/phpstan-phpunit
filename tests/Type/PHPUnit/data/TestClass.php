<?php declare(strict_types = 1);

namespace CreateMockTest;

class TestClass extends \PHPStan\Testing\TestCase
{
	public function doMocks()
	{
		$a = $this->createMock(MockedClass::class);
		$b = $this->createConfiguredMock(MockedClass::class, []);
		$c = $this->createPartialMock(MockedClass::class, []);
		$d = $this->createTestProxy(MockedClass::class);
		$e = $this->getMockForAbstractClass(MockedClass::class);
		$f = $this->getMockFromWsdl('', MockedClass::class);

		$g = $this->createMock('CreateMockTest\MockedClass');
		$h = $this->createConfiguredMock('CreateMockTest\MockedClass', []);
		$i = $this->createPartialMock('CreateMockTest\MockedClass', []);
		$j = $this->createTestProxy('CreateMockTest\MockedClass');
		$k = $this->getMockForAbstractClass('CreateMockTest\MockedClass');
		$l = $this->getMockFromWsdl('', 'CreateMockTest\MockedClass');

		$m = $this->createMock(self::class);
		$n = $this->createConfiguredMock(self::class);
		$o = $this->createPartialMock(self::class);
		$p = $this->createTestProxy(self::class);
		$q = $this->getMockForAbstractClass(self::class);
		$r = $this->getMockFromWsdl('', self::class);

		$s = $this->createMock(static::class);
		$t = $this->createConfiguredMock(static::class);
		$u = $this->createPartialMock(static::class);
		$v = $this->createTestProxy(static::class);
		$w = $this->getMockForAbstractClass(static::class);
		$x = $this->getMockFromWsdl('', static::class);

		die;
	}
}
