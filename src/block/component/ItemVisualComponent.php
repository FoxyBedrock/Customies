<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\Material;

class ItemVisualComponent implements BlockComponent {

	private GeometryComponent $geometry;
	private MaterialInstancesComponent $materialInstances;

	public function __construct(GeometryComponent $geometry, MaterialInstancesComponent $materialInstances) {
		$this->geometry = $geometry;
		$this->materialInstances = $materialInstances;
	}

	public function getName(): string {
		return 'minecraft:item_visual';
	}

	public function getValue(): array {
		return [
			"geometryDescription" => $this->geometry->getValue(),
			"materialInstancesDescription" => $this->materialInstances->getValue(Material::FLAG_FACE_DIMMING)
		];
	}

	public static function fromJson(mixed $data): static {
		return new self(
			GeometryComponent::fromJson($data["geometry"] ?? []),
			MaterialInstancesComponent::fromJson($data["material_instances"] ?? [])
		);
	}
}