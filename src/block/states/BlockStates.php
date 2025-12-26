<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\states;

interface BlockStates {

	/**
	 * Adds a state to the block.
	 * @param BlockState $trait
	 */
	public function addState(BlockState $trait): void;

	/**
	 * Checks if the block has a state by name.
	 * @param string $name
	 * @return bool
	 */
	public function hasState(string $name): bool;

	/**
	 * Retrieves a state by its name.
	 * @param string $name
	 * @return BlockState|null
	 */
	public function getState(string $name): ?BlockState;

	/**
	 * Returns all registered block states.
	 * @return BlockState[]
	 */
	public function getStates(): array;
}
