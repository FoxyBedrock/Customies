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

trait RotatableTrait {
	use HorizontalFacingTrait;

	/**
	 * @return BlockProperty[]
	 */
	public function getBlockProperties(): array {
		return [
			new BlockProperty("customies:rotation", [2, 3, 4, 5]),
		];
	}

	// This will be removed soon
	/**
	 * @return Permutation[]
	 */
	public function getPermutations(): array {
		return [
			(new Permutation("q.block_state('customies:rotation') == 2"))
				->withComponent(new TransformationComponent()),
			(new Permutation("q.block_state('customies:rotation') == 3"))
				->withComponent(new TransformationComponent(new Vector3(0, 180, 0))),
			(new Permutation("q.block_state('customies:rotation') == 4"))
				->withComponent(new TransformationComponent(new Vector3(0, 90, 0))),
			(new Permutation("q.block_state('customies:rotation') == 5"))
				->withComponent(new TransformationComponent(new Vector3(0, -90, 0))),
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