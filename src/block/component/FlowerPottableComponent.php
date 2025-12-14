<?php

namespace customiesdevs\customies\block\component;

class FlowerPottableComponent implements BlockComponent {

	public function __construct() {}

	public function getName(): string {
		return 'minecraft:embedded_visual';
	}

	public function getValue(): array {
		return [];
	}

	public static function fromJson(mixed $data): static {
		return new self();
	}
}