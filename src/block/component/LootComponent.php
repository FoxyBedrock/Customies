<?php

namespace customiesdevs\customies\block\component;

class LootComponent implements BlockComponent {

	private string $loot;

	/**
	 * The path to the loot table, relative to the behavior pack. Path string is limited to 256 characters.
	 */
	public function __construct(string $loot) {
		$this->loot = $loot;
	}

	public function getName(): string {
		return 'minecraft:loot';
	}

	public function getValue(): array {
		return [
			"value" => $this->loot
		];
	}

	public static function fromJson(mixed $data): static {
		return new self($data);
	}
}