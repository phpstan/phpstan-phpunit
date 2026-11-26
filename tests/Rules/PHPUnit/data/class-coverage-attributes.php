<?php declare(strict_types = 1);

namespace ClassCoverageAttributes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\CoversTrait;

class Covered
{

	public function covered(): void
	{
	}

}

trait CoveredTrait
{
}

function coveredFunction(): void
{
}

#[CoversClass(Covered::class)]
#[CoversClass(className: Covered::class)]
#[CoversTrait(CoveredTrait::class)]
#[CoversMethod(Covered::class, 'covered')]
#[CoversFunction('ClassCoverageAttributes\coveredFunction')]
#[CoversFunction('str_replace')]
class ValidCoversAttributes extends \PHPUnit\Framework\TestCase
{
}

#[CoversClass(\Not\A\Klass::class)]
#[CoversClass(\DateTimeInterface::class)]
#[CoversTrait(Covered::class)]
#[CoversMethod(Covered::class, 'missing')]
#[CoversMethod(\Not\A\Klass::class, 'covered')]
#[CoversFunction('ClassCoverageAttributes\missingFunction')]
class InvalidCoversAttributes extends \PHPUnit\Framework\TestCase
{
}

#[CoversClass(\Not\A\Klass::class)]
class NotATestCase
{
}
