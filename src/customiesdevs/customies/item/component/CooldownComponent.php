<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class CooldownComponent implements ItemComponent {

	public const CATEGORY_SHIELD = "shield";
	public const CATEGORY_PEARL = "ender_pearl";
	public const CATEGORY_HORN = "goat_horn";
	public const CATEGORY_WINDCHARGE = "wind_charge";
	public const CATEGORY_CHORUS = "chorusfruit";

	private string $category;
	private float $duration;

	/**
	 * The duration of time (in seconds) items with a matching category will spend cooling down before becoming usable again.
	 * @param string $category All items with the same "category" are put on cooldown when one is used.
	 * @param float $duration How long the item is on cooldown before being able to be used again.
	 */
	public function __construct(string $category, float $duration) {
		$this->category = $category;
		$this->duration = $duration;
	}

	public function getName(): string {
		return "minecraft:cooldown";
	}

	public function getValue(): array {
		return [
			"category" => $this->category,
			"duration" => $this->duration
		];
	}

	public static function fromJson(mixed $data): static {
		return new self($data["category"] ?? self::CATEGORY_SHIELD, $data["duration"] ?? 0.0);
	}
}