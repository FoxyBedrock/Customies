<?php

namespace customiesdevs\customies\block\component\custom;

use customiesdevs\customies\block\component\BlockComponent;
use pocketmine\block\BlockToolType;

class BlockBreakInfoComponent implements BlockComponent {

	public const NONE = "none";
	public const SWORD = "sword";
	public const SHOVEL = "shovel";
	public const PICKAXE = "pickaxe";
	public const AXE = "axe";
	public const SHEARS = "shears";
	public const HOE = "hoe";

	private float $hardness;
	private string $toolType;
	private int $toolHarvestLevel;
	private float $blastResistance;

	public function __construct(
		float $hardness,
		string $toolType = self::NONE,
		int $toolHarvestLevel = 0,
		?float $blastResistance = null
	) {
		$this->hardness = $hardness;
		$this->toolType = $toolType;
		$this->toolHarvestLevel = $toolHarvestLevel;
		$this->blastResistance = $blastResistance ?? $hardness * 5;
	}

	public function getName(): string {
		return "customies:block_break_info";
	}

	public function getValue(): array {
		switch($this->toolType) {
			case self::NONE:
				$toolType = BlockToolType::NONE;
				break;
			case self::SWORD:
				$toolType = BlockToolType::SWORD;
				break;
			case self::SHOVEL:
				$toolType = BlockToolType::SHOVEL;
				break;
			case self::PICKAXE:
				$toolType = BlockToolType::PICKAXE;
				break;
			case self::AXE:
				$toolType = BlockToolType::AXE;
				break;
			case self::SHEARS:
				$toolType = BlockToolType::SHEARS;
				break;
			case self::HOE:
				$toolType = BlockToolType::HOE;
				break;
			default:
				throw new \InvalidArgumentException("Invalid tool type: " . $this->toolType);
		}

		return [
			"hardness" => $this->hardness,
			"tool_type" => $toolType,
			"tool_harvest_level" => $this->toolHarvestLevel,
			"blast_resistance" => $this->blastResistance,
		];
	}

	public static function fromJson(mixed $data): static {
		return new self(
			$data["hardness"] ?? 1.0,
			$data["tool_type"] ?? self::NONE,
			$data["tool_harvest_level"] ?? 0,
			$data["blast_resistance"] ?? null
		);
	}
}