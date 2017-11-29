<?php declare(strict_types = 1);

namespace PHPStan\Type\Prophecy;

use Prophecy\Prophecy\ObjectProphecy;

class ObjectProphecyType extends \PHPStan\Type\ObjectType
{

	/**
	 * @var string
	 */
	private $mockedClass;

	public function __construct(string $mockedClass)
	{
		parent::__construct(ObjectProphecy::class);
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
