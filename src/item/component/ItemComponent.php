<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

interface ItemComponent {

	/**
	 * The component identifier, e.g. "minecraft:display_name"
	 * @return string
	 */
	public function getName(): string;

	/**
	 * The value of this component, as it would appear in an item JSON.
	 * @return mixed
	 */
	public function getValue(): mixed;

	/**
	 * Create a component instance from decoded JSON (item definition) data.
	 * Implementations should be tolerant of missing keys and apply sensible defaults.
	 * @param mixed $data The raw value found under the component identifier in an item JSON.
	 * @return static
	 */
	public static function fromJson(mixed $data): static;
}