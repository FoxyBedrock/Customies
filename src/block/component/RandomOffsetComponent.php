<?php

namespace customiesdevs\customies\block\component;

class RandomOffsetComponent implements BlockComponent {

	/**
	 * TODO Needs more data on this
	 */
	public function __construct() {
	}

	public function getName(): string {
		return 'minecraft:random_offset';
	}

	public function getValue(): array {
		return [
		];
	}

	// TODO Needs more data on this
	public static function fromJson(mixed $data): static {
		return new self();
	}
}