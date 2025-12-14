<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DurabilitySensorComponent implements ItemComponent {

	private array $durabilityThresholds;

	/**
	 * TODO needs rework
	 * Enables an item to emit effects when it receives damage. Because of this, the item also needs a `minecraft:durability` component.
	 */
	public function __construct(array $durabilityThresholds = []) {
		$this->durabilityThresholds = $durabilityThresholds;
	}

	public function getName(): string {
		return 'minecraft:durability_sensor';
	}

	public function getValue(): array {
		return [
			"durability_thresholds" => [
				$this->durabilityThresholds
			]
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}

	// I dont know if this will work | TODO test it and see what it outputs
	public function addDurabilityThreshold(int $durability, string $particleType = "", string $soundEvent = ""): self {
		$this->durabilityThresholds[] = [
			"durability" => $durability,
			"particle_type" => $particleType,
			"sound_event" => $soundEvent
		];
		return $this;
	}

	public static function fromJson(mixed $data): static {
		return new self($data["durability_thresholds"] ?? []);
	}
}