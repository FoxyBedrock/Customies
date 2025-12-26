<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\blockpermutations;

/**
 * Interface for blocks that have permutations.
 * 
 * Similar to BlockComponents but for permutations.
 */
interface BlockPermutations {

	/**
	 * Adds a permutation to the block.
	 */
	public function addPermutation(BlockPermutation $permutation): void;

	/**
	 * Adds multiple permutations at once.
	 * @param BlockPermutation[] $permutations
	 */
	public function addPermutations(array $permutations): void;

	/**
	 * Returns all registered block permutations.
	 * @return BlockPermutation[]
	 */
	public function getPermutations(): array;
}
