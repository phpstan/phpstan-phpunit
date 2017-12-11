<?php declare(strict_types = 1);

use PHPStan\Type\TypeCombinator;

require_once __DIR__ . '/../vendor/autoload.php';

TypeCombinator::setUnionTypesEnabled(true);
