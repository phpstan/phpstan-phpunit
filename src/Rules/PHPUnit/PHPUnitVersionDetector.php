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

	private ReflectionProvider $reflectionProvider;

	public function __construct(ReflectionProvider $reflectionProvider)
	{
		$this->reflectionProvider = $reflectionProvider;
	}

	public function createPHPUnitVersion(): PHPUnitVersion
	{
		$majorVersion = null;
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
							$majorVersion = (int) explode('.', $version)[0];
						}
					}
				}
			}
		}

		return new PHPUnitVersion($majorVersion);
	}

}
