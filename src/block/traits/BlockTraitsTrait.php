<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\traits;

/**
 * Trait that implements BlockTraits interface.
 * 
 * Similar to BlockComponentsTrait but for block traits.
 */
trait BlockTraitsTrait {

	/**
	 * Registered block traits indexed by trait name.
	 * @var array<string, BlockTrait>
	 */
	private array $traits = [];

	/**
	 * Adds or replaces a block trait.
	 */
	public function addTrait(BlockTrait $trait): void {
		$this->traits[$trait->getName()] = $trait;
	}

	/**
	 * Checks whether the block has a trait with the given name.
	 */
	public function hasTrait(string $name): bool {
		return isset($this->traits[$name]);
	}

	/**
	 * Retrieves a trait by its name.
	 */
	public function getTrait(string $name): ?BlockTrait {
		return $this->traits[$name] ?? null;
	}

	/**
	 * Returns all registered block traits.
	 * @return array<string, BlockTrait>
	 */
	public function getTraits(): array {
		return $this->traits;
	}
}
