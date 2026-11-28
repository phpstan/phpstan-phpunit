<?php declare(strict_types=1);

namespace DataProviderStaticFixTraitOtherFile;

trait ProvidesData
{

	public function provide(): iterable
	{
		yield [];
	}

}
