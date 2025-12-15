<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

trait BlockTrait{

	protected function getTraitsList(): ListTag {
		return new ListTag([], NBT::TAG_Compound);
	}

	public function addPlacementDirection(
		int $yRotationOffset = 0,
		bool $facingDirection = true,
		bool $cardinalDirection = false,
		bool $cornerAndCardinalDirection = false
	): CompoundTag {
		$trait = CompoundTag::create()
			->setTag("name", new StringTag("minecraft:placement_direction"))
			->setTag("enabled_states", CompoundTag::create()
				->setTag("cardinal_direction", new ByteTag($cardinalDirection ? 1 : 0))
				->setTag("corner_and_cardinal_direction", new ByteTag($cornerAndCardinalDirection ? 1 : 0))
				->setTag("facing_direction", new ByteTag($facingDirection ? 1 : 0))
			)
			->setTag("blocks_to_corner_with", new ListTag([], NBT::TAG_String))
			->setTag("y_rotation_offset", new FloatTag((float) $yRotationOffset));

		return CompoundTag::create()
			->setTag("traits", new ListTag([$trait], NBT::TAG_Compound));
	}

	public function addPlacementPosition(
		bool $blockFace = false,
		bool $verticalHalf = false
	): CompoundTag {
		$trait = CompoundTag::create()
			->setTag("name", new StringTag("minecraft:placement_position"))
			->setTag("enabled_states", CompoundTag::create()
				->setTag("block_face", new ByteTag($blockFace ? 1 : 0))
				->setTag("vertical_half", new ByteTag($verticalHalf ? 1 : 0))
			);

		return CompoundTag::create()
			->setTag("traits", new ListTag([$trait], NBT::TAG_Compound));
	}
}