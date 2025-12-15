<?php

namespace customiesdevs\customies\block\traits;

use customiesdevs\customies\block\component\BlockComponent;

trait BlockComponentsTrait {
	
	/**
	 * Registered block components indexed by component name.
	 * @var array<string, BlockComponent>
	 */
	private array $components;

	/**
	 * Adds or replaces a block component.
	 * If a component with the same name already exists, it will be overwritten.
	 * @param BlockComponent $component The component to add.
	 */
	public function addComponent(BlockComponent $component): void {
		$this->components[$component->getName()] = $component;
	}

	/**
	 * Checks whether the block has a component with the given name.
	 *
	 * @param string $name Component identifier (e.g. "minecraft:flammable")
	 * @return bool True if the component exists, false otherwise.
	 */
	public function hasComponent(string $name): bool {
		return isset($this->components[$name]);
	}

	/**
	 * Retrieves a component by its name.
	 *
	 * @param string $name Component identifier.
	 * @return BlockComponent|null The component if present, otherwise null.
	 */
	public function getComponent(string $name): ?BlockComponent {
		return $this->components[$name] ?? null;
	}

	/**
	 * Returns all registered block components.
	 *
	 * @return array<string, BlockComponent>
	 */
	public function getComponents(): array {
		return $this->components;
	}
}