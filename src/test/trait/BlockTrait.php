<?php
declare(strict_types=1);

namespace customiesdevs\customies\test\trait;

interface BlockTrait {
	public function getName(): string;

	public function getValue(): mixed;
}
