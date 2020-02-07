<?php declare(strict_types = 1);

namespace PHPStan\Type\PHPUnit;

use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\VerbosityLevel;

class MockBuilderType extends \PHPStan\Type\ObjectType
{

	/** @var array<string> */
	private $mockedClasses;

	public function __construct(
		TypeWithClassName $mockBuilderType,
		string ...$mockedClasses
	)
	{
		parent::__construct($mockBuilderType->getClassName());
		$this->mockedClasses = $mockedClasses;
	}

	/**
	 * @return array<string>
	 */
	public function getMockedClasses(): array
	{
		return $this->mockedClasses;
	}

	public function describe(VerbosityLevel $level): string
	{
		return sprintf('%s<%s>', parent::describe($level), implode('&', $this->mockedClasses));
	}

}
