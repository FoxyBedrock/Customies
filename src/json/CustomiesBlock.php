<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\blockpermutations\BlockPermutations;
use customiesdevs\customies\block\blockpermutations\BlockPermutationsTrait;
use customiesdevs\customies\block\component\BlockComponents;
use customiesdevs\customies\block\component\BlockComponentsTrait;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\block\utils\PillarRotationTrait;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\item\Item;
use pocketmine\math\Axis;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class CustomiesBlock extends Block implements BlockComponents, BlockPermutations {
	use BlockComponentsTrait;
	use BlockPermutationsTrait;

	use HorizontalFacingTrait;
	use PillarRotationTrait;

	/**
	 * Constructs a new CustomiesBlock with the given configuration.
	 *
	 * @param array<string, mixed> $components Associative array of component identifiers to data
	 * @param array<string, mixed> $states Associative array of state identifiers to values
	 * @param array<int, array{condition: string, components: array}> $permutations Array of permutation definitions
	 * @param float $hardness The hardness of the block (default 1.0)
	 * @param int $toolType The required tool type to break the block (default BlockToolType::NONE)
	 * @param int $toolHarvestLevel The required tool harvest level (default 0)
	 * @param float|null $blastResistance Optional blast resistance
	 */
	public function __construct(
		array $components,
		float $hardness = 1.0,
		int $toolType = BlockToolType::NONE,
		int $toolHarvestLevel = 0,
		?float $blastResistance = null
	) {
		// Construct the base Block with identifier and block info
		parent::__construct(
			new BlockIdentifier(BlockTypeIds::newId()), 
			"Custom Block", 
			new BlockTypeInfo(new BlockBreakInfo(
				$hardness,
				$toolType,
				$toolHarvestLevel,
				$blastResistance
			))
		);

		// Add all registered components
		foreach($components as $name => $data) {
			$component = BlockComponentRegistry::fromJson($name, $data);
			if($component !== null) {
				$this->addComponent($component);
			}
		}
	}

	public function getCurrentStates(): array {
		return [$this->axis];
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool {
		$this->axis = match($face) {
			0, 1 => Axis::Y,  // down, up
			2, 3 => Axis::Z,  // north, south
			4, 5 => Axis::X,  // west, east
			default => Axis::Y
		};
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function serializeState(BlockStateWriter $out): void {
		$rotation = match($this->axis) {
			Axis::X => "east",
			Axis::Y => "up",
			Axis::Z => "north",
			default => "down"
		};
		$out->writeString("minecraft:block_face", $rotation);
	}

	public function deserializeState(BlockStateReader $in): void {
		$this->axis = match($in->readString("minecraft:block_face")) {
			"east", "west" => Axis::X,
			"up", "down" => Axis::Y,
			"north", "south" => Axis::Z,
			default => Axis::Y
		};
	}
}