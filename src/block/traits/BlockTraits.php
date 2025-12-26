<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\traits;

interface BlockTraits {

	/**
	 * Adds a trait to the block.
	 * @param BlockTrait $trait
	 */
	public function addTrait(BlockTrait $trait): void;

	/**
	 * Checks if the block has a trait by name.
	 * @param string $name
	 * @return bool
	 */
	public function hasTrait(string $name): bool;

	/**
	 * Retrieves a trait by its name.
	 * @param string $name
	 * @return BlockTrait|null
	 */
	public function getTrait(string $name): ?BlockTrait;

	/**
	 * Returns all traits of the block.
	 * @return BlockTrait[]
	 */
	public function getTraits(): array;
}
