<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\component;

interface BlockComponent {

	/**
	 * The component identifier, e.g. "minecraft:collision_box"
	 * @return string
	 */
	public function getName(): string;

	/**
	 * The value of this component, as it would appear in a block JSON.
	 * @return mixed
	 */
	public function getValue(): mixed;

	/**
	 * Create a component instance from decoded JSON (block definition) data.
	 * Implementations should be tolerant of missing keys and apply sensible defaults.
	 * @param mixed $data The raw value found under the component identifier in a block JSON.
	 * @return static
	 */
	public static function fromJson(mixed $data): static;
}