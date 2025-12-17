<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use customiesdevs\customies\block\component\TransformationComponent;
use pocketmine\block\Block;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

// a replacement for rotatable trait that uses cardinal_direction block state but we should implement block trait, states and permutations the same way components are implemented
trait CardinalDirectionRotationTrait {
	use HorizontalFacingTrait;

	/**
	 * @return BlockProperty[]
	 */
	public function getBlockProperties(): array {
		return [
			new BlockProperty("customies:rotation", [2, 3, 4, 5]),
		];
	}

	/**
	 * @return Permutation[]
	 */
	public function getPermutations(): array {
		return [
			(new Permutation("q.block_state('minecraft:cardinal_direction') == 'north'"))
				->withComponent(new TransformationComponent()),
			(new Permutation("q.block_state('minecraft:cardinal_direction') == 'west'"))
				->withComponent(new TransformationComponent(
					new Vector3(0, 90, 0)
				)),
			(new Permutation("q.block_state('minecraft:cardinal_direction') == 'south'"))
				->withComponent(new TransformationComponent(
					new Vector3(0, 180, 0)
				)),
			(new Permutation("q.block_state('minecraft:cardinal_direction') == 'east'"))
				->withComponent(new TransformationComponent(
					new Vector3(0, -90, 0)
				)),
		];
	}

	public function getCurrentBlockProperties(): array {
		return [$this->facing];
	}

	protected function writeStateToMeta(): int {
		return Permutations::toMeta($this);
	}

	public function readStateFromData(int $id, int $stateMeta): void {
		$blockProperties = Permutations::fromMeta($this, $stateMeta);
		$this->facing = $blockProperties[0] ?? Facing::NORTH;
	}

	public function getStateBitmask(): int {
		return Permutations::getStateBitmask($this);
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool {
		if($player !== null) {
			$this->facing = $player->getHorizontalFacing();
		}
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function serializeState(BlockStateWriter $out): void {
		$out->writeInt("customies:rotation", $this->facing);
	}

	public function deserializeState(BlockStateReader $in): void {
		$this->facing = $in->readInt("customies:rotation");
	}
}