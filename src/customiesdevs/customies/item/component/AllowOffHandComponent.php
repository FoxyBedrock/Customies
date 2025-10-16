<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class AllowOffHandComponent implements ItemComponent {

	private bool $offHand;

	/**
	 * Determine whether an item can be placed in the off-hand slot of the inventory.
	 * @param bool $offHand Default is set to `true`
	 */
	public function __construct(bool $offHand = true) {
		$this->offHand = $offHand;
	}

	public function getName(): string {
		return VanillaItemComponents::ALLOW_OFF_HAND;
	}

	public function getValue(): array {
		return [
			"value" => $this->offHand
		];
	}

	public static function fromJson(mixed $data): static {
		return new self($data ?? true);
	}
}