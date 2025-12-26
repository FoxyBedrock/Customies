<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\traits;

interface BlockTrait {

	/**
	 * Returns the name of the trait.
	 */
	public function getName(): string;

	/**
	 * Returns the value of the trait.
	 */
	public function getValue(): mixed;
}
