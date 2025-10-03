<?php

namespace customiesdevs\customies\block\component;

class ItemVisualComponent implements BlockComponent {

	private GeometryComponent $geometry;
	private MaterialInstancesComponent $materialInstances;

	public function __construct(GeometryComponent $geometry, MaterialInstancesComponent $materialInstances) {
		$this->geometry = $geometry;
		$this->materialInstances = $materialInstances;
	}

	public function getName(): string {
		return VanillaBlockComponents::ITEM_VISUAL;
	}

	public function getValue(): array {
		return [
			"geometry" => $this->geometry->getValue(),
			"material_instances" => $this->materialInstances->getValue()
		];
	}

	public static function fromJson(mixed $data): static {
		return new self(
			GeometryComponent::fromJson($data["geometry"] ?? []),
			MaterialInstancesComponent::fromJson($data["material_instances"] ?? [])
		);
	}
}