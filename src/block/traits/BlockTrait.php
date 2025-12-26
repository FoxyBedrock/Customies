<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\traits;

interface BlockTrait {
	public function getName(): string;

	public function getValue(): mixed;
}
