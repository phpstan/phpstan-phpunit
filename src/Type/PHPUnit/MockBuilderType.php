<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\VerbosityLevel;

class MockBuilderType extends \PHPStan\Type\ObjectType
{

	/** @var string */
	private $mockedClass;

	public function __construct(
		TypeWithClassName $mockBuilderType,
		string $mockedClass
	)
	{
		parent::__construct($mockBuilderType->getClassName());
		$this->mockedClass = $mockedClass;
	}

	public function getMockedClass(): string
	{
		return $this->mockedClass;
	}

	public function describe(VerbosityLevel $level): string
	{
		return sprintf('%s<%s>', parent::describe($level), $this->mockedClass);
	}

}
