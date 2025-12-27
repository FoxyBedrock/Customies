<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\permutations\BlockPermutation;

/**
 * Registry for block permutation mappings.
 * Creates BlockPermutation instances from JSON data.
 */
final class BlockPermutationRegistry {

	/**
	 * Create BlockPermutations from JSON permutation array.
	 *
	 * JSON format:
	 * [
	 *   {
	 *     "condition": "q.block_state('minecraft:cardinal_direction') == 'north'",
	 *     "components": {
	 *       "minecraft:transformation": { "rotation": [0, 0, 0] }
	 *     }
	 *   }
	 * ]
	 *
	 * @param array<int, array{condition: string, components: array}> $data
	 * @return BlockPermutation[]
	 */
	public static function fromJson(array $data): array {
		$permutations = [];
		
		foreach($data as $permutation) {
			if(!isset($permutation['condition'], $permutation['components'])) {
				continue;
			}
			
			$condition = $permutation['condition'];
			$components = $permutation['components'];
			
			// Each permutation should have exactly one component (typically transformation)
			foreach($components as $componentName => $componentData) {
				$component = BlockComponentRegistry::fromJson($componentName, $componentData);
				if($component !== null) {
					$permutations[] = new BlockPermutation($condition, $component);
					break; // Only take the first component per permutation
				}
			}
		}
		
		return $permutations;
	}
}
