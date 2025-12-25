<?php
declare(strict_types=1);

namespace customiesdevs\customies\test\permutation;

use customiesdevs\customies\block\component\BlockComponent;

class BlockPermutation {

	public function __construct(
		private readonly string $condition,
		private readonly BlockComponent $components
	) {}

	public function getCondition(): string {
		return $this->condition;
	}

	public function getComponents(): BlockComponent {
		return $this->components;
	}

	public function toArray(): array {
		return [
			"condition" => $this->condition,
			"components" => [
				$this->components->getName() => $this->components->getValue()
			]
		];
	}
}
