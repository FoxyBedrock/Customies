<?php

namespace customiesdevs\customies\block\component;

class MovableComponent implements BlockComponent {

	/** Block can be pushed and pulled by pistons (default). */
	public const MOVEMENT_TYPE_PUSH_PULL = "push_pull";
	/** Block can be pushed but not pulled by sticky pistons. */
	public const MOVEMENT_TYPE_PUSH = "push";
	/** Block is destroyed when moved by a piston. */
	public const MOVEMENT_TYPE_POPPED = "popped";
	/** Block cannot be moved by pistons at all. */
	public const MOVEMENT_TYPE_IMMOVABLE = "immovable";
	/**
	 * Adjacent blocks with compatible rules will be moved together.
	 * Only works with {@see MOVEMENT_TYPE_PUSH_PULL}.
	 */
	public const STICKY_SAME = "same";
	/** Default behavior; does not move adjacent blocks. */
	public const STICKY_NONE = "none";

	/** @var string How the block reacts to piston movement. */
	private string $movementType;
	/** @var string How the block interacts with adjacent blocks when moved. */
	private string $sticky;

	/**
	 * Creates a new movable component definition.
	 * @param string $movementType
	 * Determines how the block reacts to piston movement.
	 * Must be one of:
	 * - {@see MOVEMENT_TYPE_PUSH_PULL}
	 * - {@see MOVEMENT_TYPE_PUSH}
	 * - {@see MOVEMENT_TYPE_POPPED}
	 * - {@see MOVEMENT_TYPE_IMMOVABLE}
	 * @param string $sticky
	 * Determines whether adjacent blocks are moved together.
	 * Must be one of:
	 * - {@see STICKY_NONE}
	 * - {@see STICKY_SAME}
	 */
	public function __construct(
		string $movementType = self::MOVEMENT_TYPE_PUSH_PULL,
		string $sticky = self::STICKY_NONE
	) {
		$this->movementType = $movementType;
		$this->sticky = $sticky;
	}

	public function getName(): string {
		return 'minecraft:movable';
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