<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\traits;

interface BlockTraits {

	public function addTrait(BlockTrait $trait): void;
	public function hasTrait(string $name): bool;
	public function getTrait(string $name): ?BlockTrait;
	/**
	 * @return BlockTrait[]
	 */
	public function getTraits(): array;
}
