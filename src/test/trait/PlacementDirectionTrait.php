<?php

namespace customiesdevs\customies\test\trait;

class PlacementDirectionTrait implements BlockTrait {

	public function __construct(
		private readonly array $state, 
		private readonly float $yRotationOffset
	) {}

	public function getName(): string {
		return "minecraft:placement_direction";
	}

	public function getValue(): array {
		return [
			"blocks_to_corner_with" => [],
			"enabled_states" => [ 
				"cardinal_direction" => in_array("minecraft:cardinal_direction", $this->state, true),
				"corner_and_cardinal_direction" => in_array("minecraft:corner_and_cardinal_direction", $this->state, true),
				"facing_direction" => in_array("minecraft:facing_direction", $this->state, true),
			],
			"name" => $this->getName(),
			"y_rotation_offset" => $this->yRotationOffset
		];
	}
}