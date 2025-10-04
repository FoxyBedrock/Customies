<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DamageAbsorptionComponent implements ItemComponent {

	private array $absorbableCauses;

	/**
	 * It allows an item to absorb damage that would otherwise be dealt to its wearer.
	 * For this to happen, the item needs to be equipped in an armor slot.
	 * The absorbed damage reduces the item's durability, with any excess damage being ignored.
	 * Because of this, the item also needs a `minecraft:durability` component.
	 * @param array $absorbableCauses List of damage causes that can be absorbed by the item. By default, no damage cause is absorbed. Value must have at least 1 items.
	 */
	public function __construct(array $absorbableCauses) {
		$this->absorbableCauses = $absorbableCauses;
	}

	public function getName(): string {
		return "minecraft:damage_absorption";
	}

	public function getValue(): array {
		return [
			"absorbable_causes" => $this->absorbableCauses
		];
	}

	public static function fromJson(mixed $data): static {
		return new self($data["absorbable_causes"] ?? []);
	}
}