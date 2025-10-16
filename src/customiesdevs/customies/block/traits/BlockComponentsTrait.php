<?php

namespace customiesdevs\customies\block\traits;

use customiesdevs\customies\block\component\BlockComponent;

trait BlockComponentsTrait {
	
	/** @var BlockComponent[] */
	private array $components;

	public function addComponent(BlockComponent $component): void {
		$this->components[$component->getName()] = $component;
	}

	public function hasComponent(string $name): bool {
		return isset($this->components[$name]);
	}

	public function getComponent(string $name): ?BlockComponent {
		return $this->components[$name] ?? null;
	}

	/**
	 * @return BlockComponent[]
	 */
	public function getComponents(): array {
		return $this->components;
	}
}