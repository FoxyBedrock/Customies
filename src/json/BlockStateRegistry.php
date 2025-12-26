<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\states\BlockState;
use customiesdevs\customies\block\states\BooleanState;
use customiesdevs\customies\block\states\IntRangeState;
use customiesdevs\customies\block\states\IntState;
use customiesdevs\customies\block\states\StringState;

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
	 *
	 * @param string $name State identifier
	 * @param mixed $data JSON-decoded data for the state
	 * @return BlockState|null Returns the state instance or null if invalid
	 */
	public static function fromJson(string $name, mixed $data): ?BlockState {
		// Handle range format: { "values": { "min": 0, "max": 15 } }
		if(is_array($data) && isset($data['values']['min'], $data['values']['max'])) {
			return new IntRangeState(
				$name,
				(int) $data['values']['min'],
				(int) $data['values']['max']
			);
		}

		// Handle array format
		if(is_array($data) && $data !== []) {
			$firstValue = $data[0] ?? null;

			// Boolean state: [false, true]
			if(is_bool($firstValue)) {
				return new BooleanState($name);
			}

			// Integer state: [0, 1, 2, 3]
			if(is_int($firstValue)) {
				return new IntState($name, array_map('intval', $data));
			}

			// String state: ["north", "south", "east", "west"]
			if(is_string($firstValue)) {
				return new StringState($name, array_map('strval', $data));
			}
		}

		return null;
	}
}
