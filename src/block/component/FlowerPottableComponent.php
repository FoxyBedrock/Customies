<?php

namespace customiesdevs\customies\block\component;

class FlowerPottableComponent implements BlockComponent {

	public function __construct() {}

	public function getName(): string {
		return 'minecraft:flower_pottable';
	}

	public function getValue(): array {
		return [];
	}
}