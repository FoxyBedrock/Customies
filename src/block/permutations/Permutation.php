<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use customiesdevs\customies\block\component\BlockComponent;
use customiesdevs\customies\block\component\MaterialInstancesComponent;
use customiesdevs\customies\util\NBT;
use pocketmine\nbt\tag\CompoundTag;

final class Permutation {

	private CompoundTag $components;

	public function __construct(private readonly string $condition) {
		$this->components = CompoundTag::create();
	}

	/**
	 * Returns the permutation with the provided component added to the current list of components.
	 */
	public function withComponent(string $component, mixed $value) : self {
		if ($value instanceof MaterialInstancesComponent) {
			$value = $value->getValue(4); // Use packed_bools = 4 for permutations
		} elseif ($value instanceof BlockComponent) {
			$value = $value->getValue();
		}
		$this->components->setTag($component, NBT::getTagType($value));
		return $this;
	}

	/**
	 * Returns the permutation in the correct NBT format supported by the client.
	 */
	public function toNBT(): CompoundTag {
		return CompoundTag::create()
			->setString("condition", $this->condition)
			->setTag("components", $this->components);
	}
}