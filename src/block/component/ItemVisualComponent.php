<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\Material;
use pocketmine\nbt\tag\ByteTag;

class ItemVisualComponent implements BlockComponent {

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
		return 'minecraft:item_visual';
	}

	public function getValue(): array {
		$materials = [];
		foreach($this->materials as $material){
			$materials[$material->getTarget()] = [
				"packed_bools" => new ByteTag(1),
				...$material->toArray()
			];
		}
		return [
			"geometryDescription" => $this->geometry->getValue(),
			"materialInstancesDescription" => [
				"mappings" => [],
				"materials" => $materials
			]
		];
	}

	public static function fromJson(mixed $data): static {
		$materials = [];
		foreach($data as $target => $materialData){
			$materials[] = Material::fromArray($target, $materialData);
		}
		return new self(
			GeometryComponent::fromJson($data["geometry"] ?? []),
			$materials
		);
	}
}