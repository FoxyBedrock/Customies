<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\states;

use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;

interface BlockState {

	/**
	 * Returns the state property name (e.g., "minecraft:cardinal_direction").
	 */
	public function getName(): string;

	/**
	 * Returns the NBT value definition for client (enum + name).
	 * The "enum" values must be native PHP types (bool, int, string) that
	 * NBT::getTagType() can convert to the correct NBT tag type.
	 */
	public function getValue(): array;

	/**
	 * Gets the current state value.
	 */
	public function getCurrentValue(): mixed;

	/**
	 * Sets the current state value.
	 */
	public function setCurrentValue(mixed $value): void;

	/**
	 * Writes the current state value to the BlockStateWriter.
	 */
	public function serialize(BlockStateWriter $writer): void;

	/**
	 * Reads the state value from the BlockStateReader and sets it.
	 */
	public function deserialize(BlockStateReader $reader): void;

}
