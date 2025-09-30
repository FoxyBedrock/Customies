<?php

namespace customiesdevs\customies\block\component;

class PlacementFilterComponent implements BlockComponent {

	private array $allowedFaces;
	private array $blockFilter;

	/**
	 * TODO Needs more data on this
	 */
	public function __construct(array $allowedFaces = [], array $blockFilter = []) {
		$this->allowedFaces = $allowedFaces;
		$this->blockFilter = $blockFilter;
	}

	public function getName(): string {
		return "minecraft:placement_filter";
	}

	public function getValue(): array {
		return [
			"conditions" => [
				[
					"allowed_faces" => $this->allowedFaces,
					"block_filter" => $this->blockFilter                    
				]
			]
		];
	}
}