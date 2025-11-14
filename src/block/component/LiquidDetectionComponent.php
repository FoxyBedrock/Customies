<?php

namespace customiesdevs\customies\block\component;

class LiquidDetectionComponent implements BlockComponent {

	public const BLOCKING = "blocking";
	public const BROKEN = "broken";
	public const POPPED = "popped";
	public const NO_REACTION = "no_reaction";

	private string $liquidType;
	private bool $canContainLiquid;
	private string $onLiquidTouches;
	private array $stopsLiquidFlowingFromDirection;

	/**
	 * @param string $liquidType The type of liquid this detection rule is for. Currently, water is the only supported liquid type. If this field is omitted, water will be the liquid type by default.
	 * @param bool $canContainLiquid Whether this block can contain the liquid. For example, if the liquid type is water, this means the block can be waterlogged.
	 * @param string $onLiquidTouches How the block reacts to flowing water. Must be one of the following options:
					- "blocking" - The default value for this field. The block stops the liquid from flowing.
					- "broken" - The block is destroyed completely.
					- "popped" - The block is destroyed and its item is spawned.
					- "no_reaction" - The block is unaffected; visually, the liquid will flow through the block.
	 * @param array $stopsLiquidFlowingFromDirection When a block contains a liquid, controls the directions in which the liquid can't flow out from the block. Also controls the directions in which a block can stop liquid flowing into it if no_reaction is set for the on_liquid_touches field. Can be a list of the following directions: "up", "down", "north", "south", "east", "west". The default is an empty list; this means that liquid can flow out of all directions by default.	
	 */
	public function __construct(string $liquidType = "water", bool $canContainLiquid = false, string $onLiquidTouches = self::BLOCKING, array $stopsLiquidFlowingFromDirection = []) {
		$this->liquidType = $liquidType;
		$this->canContainLiquid = $canContainLiquid;
		$this->onLiquidTouches = $onLiquidTouches;
		$this->stopsLiquidFlowingFromDirection = $stopsLiquidFlowingFromDirection;
	}

	public function getName(): string {
		return 'minecraft:liquid_detection';
	}

	public function getValue(): array {
		return [
			"detectionRules" => [
				[
					"liquidType" => $this->liquidType,
					"canContainLiquid" => $this->canContainLiquid,
					"onLiquidTouches" => $this->onLiquidTouches,
					"stopsLiquidFromDirection" => $this->stopsLiquidFlowingFromDirection,
				]				
			]
		];
	}

	public static function fromJson(mixed $data): static {
		$rule = $data["detectionRules"][0] ?? [];
		return new self(
			$rule["liquid_type"] ?? "water",
			$rule["can_contain_liquid"] ?? false,
			$rule["on_liquid_touches"] ?? self::BLOCKING,
			$rule["stops_liquid_flowing_from_direction"] ?? []
		);
	}
}