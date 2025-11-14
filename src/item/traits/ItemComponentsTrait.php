<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\traits;

use customiesdevs\customies\item\component\ItemComponent;

trait ItemComponentsTrait {

	/** @var ItemComponent[] */
	private array $components;

	public function addComponent(ItemComponent $component): void {
		$this->components[$component->getName()] = $component;
	}

	public function hasComponent(string $name): bool {
		return isset($this->components[$name]);
	}

	public function getComponent(string $name): ?ItemComponent {
		return $this->components[$name] ?? null;
	}

	/**
	 * @return ItemComponent[]
	 */
	public function getComponents(): array {
		return $this->components;
	}
}
