<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\blockpermutations;

/**
 * Trait that implements BlockPermutations interface.
 * 
 * Similar to BlockComponentsTrait but for permutations.
 */
trait BlockPermutationsTrait {

	/**
	 * Registered block permutations.
	 * @var BlockPermutation[]
	 */
	private array $permutations = [];

	/**
	 * Adds a permutation to the block.
	 */
	public function addPermutation(BlockPermutation $permutation): void {
		$this->permutations[] = $permutation;
	}

	/**
	 * Adds multiple permutations at once.
	 * @param BlockPermutation[] $permutations
	 */
	public function addPermutations(array $permutations): void {
		foreach($permutations as $permutation) {
			$this->addPermutation($permutation);
		}
	}

	/**
	 * Returns all registered block permutations.
	 * @return BlockPermutation[]
	 */
	public function getPermutations(): array {
		return $this->permutations;
	}
}
