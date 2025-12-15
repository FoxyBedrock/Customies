<?php
declare(strict_types=1);

namespace customiesdevs\customies\util;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\Tag;
use function array_keys;
use function array_map;
use function count;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function range;

class NBT {

	/**
	 * Attempts to return the correct NBT Tag for the provided PHP value.
	 * Supported conversions:
	 * - array      → ListTag or CompoundTag
	 * - bool       → ByteTag
	 * - float      → FloatTag
	 * - int        → IntTag
	 * - string     → StringTag
	 * - CompoundTag → Returned as-is
	 *
	 * @param mixed $type The value to convert into an NBT Tag
	 * @return Tag|null Returns the corresponding Tag instance, or null if the
	 *                  type cannot be converted.
	 */
	public static function getTagType($type): ?Tag {
		return match (true) {
			is_array($type) => self::getArrayTag($type),
			is_bool($type) => new ByteTag($type ? 1 : 0),
			is_float($type) => new FloatTag($type),
			is_int($type) => new IntTag($type),
			is_string($type) => new StringTag($type),
			$type instanceof CompoundTag => $type,
			default => null,
		};
	}

	/**
	 * Creates an NBT Tag from an array.
	 * - If the array uses sequential numeric keys (0..n), a ListTag is created.
	 * - Otherwise, a CompoundTag is created with each key mapped to a Tag.
	 * @param array $array The array to convert into an NBT Tag
	 * @return Tag Returns either a ListTag or CompoundTag depending on the array structure
	 */
	private static function getArrayTag(array $array): Tag {
		if(array_keys($array) === range(0, count($array) - 1)) {
			return new ListTag(array_map(fn($value) => self::getTagType($value), $array));
		}
		$tag = CompoundTag::create();
		foreach($array as $key => $value){
			$tag->setTag($key, self::getTagType($value));
		}
		return $tag;
	}
}