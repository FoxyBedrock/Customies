<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\states\BlockState;

/**
 * Registry for block state mappings.
 * Creates BlockState instances from JSON data.
 */
final class BlockStateRegistry {

	/**
	 * Create a BlockState from JSON state definition.
	 *
	 * JSON format examples:
	 * - Boolean: "customies:powered": [false, true]
	 * - Integer: "customies:level": [0, 1, 2, 3]
	 * - Integer range: "customies:age": { "values": { "min": 0, "max": 15 } }
	 * - String: "customies:color": ["red", "green", "blue"]
	 * - Bedrock built-in: "minecraft:block_face": true (uses predefined state class)
	 *
	 * @param string $name State identifier
	 * @param mixed $data JSON-decoded data for the state
	 * @return BlockState|null Returns the state instance or null if invalid
	 */
	public static function fromJson(string $name, mixed $data): ?BlockState {

		// Handle range format: { "values": { "min": 0, "max": 15 } }
		if(is_array($data) && isset($data['values']['min'], $data['values']['max'])) {
			$min = (int) $data['values']['min'];
			$max = (int) $data['values']['max'];
			return new BlockState($name, range($min, $max));
		}

		// Handle array format - BlockState auto-detects type from values
		if(is_array($data) && $data !== []) {
			return new BlockState($name, $data);
		}

		return null;
	}
}
