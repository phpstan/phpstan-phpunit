<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Reflection\ReflectionProvider;
use PHPUnit\Framework\TestCase;
use function dirname;
use function explode;
use function file_get_contents;
use function is_file;
use function json_decode;

class PHPUnitVersionDetector
{

	private bool $initialized = false;

	private ?int $majorVersion = null;

	private ReflectionProvider $reflectionProvider;

	public function __construct(ReflectionProvider $reflectionProvider)
	{
		$this->reflectionProvider = $reflectionProvider;
	}

	public function isPHPUnit10OrNewer(): bool
	{
		$majorVersion = $this->getMajorVersion();
		return $majorVersion !== null && $majorVersion >= 10;
	}

	private function getMajorVersion(): ?int
	{
		if ($this->initialized) {
			return $this->majorVersion;
		}
		$this->initialized = true;

		if ($this->reflectionProvider->hasClass(TestCase::class)) {
			$testCase = $this->reflectionProvider->getClass(TestCase::class);
			$file = $testCase->getFileName();
			if ($file !== null) {
				$phpUnitRoot = dirname($file, 3);
				$phpUnitComposer = $phpUnitRoot . '/composer.json';
				if (is_file($phpUnitComposer)) {
					$composerJson = @file_get_contents($phpUnitComposer);
					if ($composerJson !== false) {
						$json = json_decode($composerJson, true);
						$version = $json['extra']['branch-alias']['dev-main'] ?? null;
						if ($version !== null) {
							$this->majorVersion = (int) explode('.', $version)[0];
						}
					}
				}
			}
		}

		return $this->majorVersion;
	}

}
