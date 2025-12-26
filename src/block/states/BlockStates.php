<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\states;

interface BlockStates {

	public function addState(BlockState $trait): void;
	public function hasState(string $name): bool;
	public function getState(string $name): ?BlockState;
	/**
	 * @return BlockState[]
	 */
	public function getStates(): array;
}
