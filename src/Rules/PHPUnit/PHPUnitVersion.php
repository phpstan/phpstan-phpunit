<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

class PHPUnitVersion
{

	private ?int $majorVersion;

	public function __construct(?int $majorVersion)
	{
		$this->majorVersion = $majorVersion;
	}

	public function supportsDataProviderAttribute(): bool
	{
		return $this->majorVersion !== null && $this->majorVersion >= 10;
	}

	public function supportsTestAttribute(): bool
	{
		return $this->majorVersion !== null && $this->majorVersion >= 10;
	}

	public function requiresStaticDataProviders(): bool
	{
		return $this->majorVersion !== null && $this->majorVersion >= 10;
	}

}
