<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\TrinaryLogic;

class PHPUnitVersion
{

	private ?int $majorVersion;

	public function __construct(?int $majorVersion)
	{
		$this->majorVersion = $majorVersion;
	}

	public function supportsDataProviderAttribute(): TrinaryLogic
	{
		if ($this->majorVersion === null) {
			return TrinaryLogic::createMaybe();
		}
		return TrinaryLogic::createFromBoolean($this->majorVersion >= 10);
	}

	public function supportsTestAttribute(): TrinaryLogic
	{
		if ($this->majorVersion === null) {
			return TrinaryLogic::createMaybe();
		}
		return TrinaryLogic::createFromBoolean($this->majorVersion >= 10);
	}

	public function requiresStaticDataProviders(): TrinaryLogic
	{
		if ($this->majorVersion === null) {
			return TrinaryLogic::createMaybe();
		}
		return TrinaryLogic::createFromBoolean($this->majorVersion >= 10);
	}

	public function supportsNamedArgumentsInDataProvider(): TrinaryLogic
	{
		if ($this->majorVersion === null) {
			return TrinaryLogic::createMaybe();
		}
		return TrinaryLogic::createFromBoolean($this->majorVersion >= 11);
	}

}
