<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class LightEmissionComponent implements BlockComponent {

	private int $emission;

	/**
	 * The amount of light this block will emit in a range (0-15). Higher value means more light will be emitted.
	 * @param int $emission
	 */
	public function __construct(int $emission = 0) {
		$this->emission = $emission;
	}

	public function getName(): string {
		return VanillaBlockComponents::LIGHT_EMISSION;
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setByte("emission", $this->emission);
	}

	public static function fromJson(mixed $data): static {
		return new self($data ?? 0);
	}
}