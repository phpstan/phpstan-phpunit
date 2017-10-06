<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

class MockBuilderType extends \PHPStan\Type\ObjectType
{

	/**
	 * @var string
	 */
	private $mockedClass;

	public function __construct(string $mockedClass)
	{
		parent::__construct(\PHPUnit_Framework_MockObject_MockBuilder::class);
		$this->mockedClass = $mockedClass;
	}

	public function getMockedClass(): string
	{
		return $this->mockedClass;
	}

	public function describe(): string
	{
		return sprintf('%s<%s>', parent::describe(), $this->mockedClass);
	}

}
