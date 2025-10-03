<?php

namespace customiesdevs\customies\block\component;

class MovableComponent implements BlockComponent {

	public const MOVEMENT_TYPE_PUSH_PULL = "push_pull";
	public const MOVEMENT_TYPE_PUSH = "push";
	public const MOVEMENT_TYPE_POPPED = "popped";
	public const MOVEMENT_TYPE_IMMOVABLE = "immovable";

	public const STICKY_SAME = "same";
	public const STICKY_NONE = "none";

	private string $movementType;
	private string $sticky;

	/**
	 * Determines how a block can be moved by pistons.
	 * @param string $movementType [Required] How the block reacts to being pushed by another block like a piston. Must be one of the following options:
					- "push_pull" - The default value for this field. The block will be pushed and pulled by a piston.
					- "push" - The block will only be pulled by a piston and will ignore a sticky piston.
					- "popped" - The block is destroyed when moved by a piston.
					- "immovable" - The block is unaffected by a piston.
	 * @param string $sticky [Optional] How the block should handle adjacent blocks around it when being pushed by another block like a piston. Must be one of the following options:
					- "same" - Adjacent blocks to this block will be moved when moved. This excludes other blocks with the "same" property. This will only work with the movement_type: "push_pull".
					- "none" - The default and will not move adjacent blocks.
	 */
	public function __construct(string $movementType = self::MOVEMENT_TYPE_PUSH_PULL, string $sticky = self::STICKY_NONE) {
		$this->movementType = $movementType;
		$this->sticky = $sticky;
	}

	public function getName(): string {
		return VanillaBlockComponents::MOVABLE;
	}

	public function getValue(): array {
		return [
			"movement_type" => $this->movementType,
			"sticky" => $this->sticky
		];
	}

	public static function fromJson(mixed $data): static {
		return new self(
			$data["movement_type"] ?? self::MOVEMENT_TYPE_PUSH_PULL,
			$data["sticky"] ?? self::STICKY_NONE
		);
	}
}