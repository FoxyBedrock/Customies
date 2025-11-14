<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DamageComponent implements ItemComponent {

	private int $damage;

	/**
	 * Determines how much extra damage the item does on attack. 
	 * Note that this must be a positive value.
	 * @param int $damage Specifies how much extra damage the item does, must be a positive number.
	 */
	public function __construct(int $damage) {
		$this->damage = $damage;
	}

	public function getName(): string {
		return 'minecraft:damage';
	}

	public function getValue(): array {
		return [
			"value" => $this->damage
		];
	}

	public static function fromJson(mixed $data): static {
		return new self($data ?? 0);
	}
}