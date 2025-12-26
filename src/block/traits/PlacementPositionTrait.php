<?php

namespace customiesdevs\customies\block\traits;

class PlacementPositionTrait implements BlockTrait {

	public function __construct(
		private readonly array $state,
	) {}

	public function getName(): string {
		return "minecraft:placement_position";
	}

	public function getValue(): array {
		return [
			"enabled_states" => [ 
				"block_face" => in_array("minecraft:block_face", $this->state, true),
				"vertical_half" => in_array("minecraft:vertical_half", $this->state, true),
			],
			"name" => $this->getName()
		];
	}
}