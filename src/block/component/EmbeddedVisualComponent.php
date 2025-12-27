<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\Material;

class EmbeddedVisualComponent implements BlockComponent {

	public function __construct(
		private readonly GeometryComponent $geometry, 
		private readonly array $materials
	) {
		if(count($materials) === 0){
			throw new \InvalidArgumentException("At least one material must be defined");
		}
		foreach($materials as $material){
			if(!$material instanceof Material){
				throw new \InvalidArgumentException("All materials must be instances of ".Material::class);
			}
		}
	}

	public function getName(): string {
		return 'minecraft:embedded_visual';
	}

	public function getValue(): array {
		$materials = [];
		foreach($this->materials as $material){
			$materials[$material->getTarget()] = [
				"alpha_masked_tint" => false,
				"face_dimming" => true,
				"isotropic" => false,
				...$material->toArray()
			];
		}
		return [
			"geometry" => $this->geometry->getValue(),
			"material_instances" => $materials
		];
	}
}