<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\traits;

trait BlockTraitsTrait {

	/**
	 * Registered block traits indexed by trait name.
	 * @var array<string, BlockTrait>
	 */
	private array $traits = [];

	/**
	 * Adds a block trait.
	 * @param BlockTrait $trait
	 */
	public function addTrait(BlockTrait $trait): void {
		$this->traits[$trait->getName()] = $trait;
	}

	/**
	 * Checks whether the block has a trait with the given name.
	 * @param string $name
	 * @return bool
	 */
	public function hasTrait(string $name): bool {
		return isset($this->traits[$name]);
	}

	/**
	 * Retrieves a trait by its name.
	 * @param string $name
	 * @return BlockTrait|null
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
