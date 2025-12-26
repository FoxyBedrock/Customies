<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\traits\BlockTrait;
use customiesdevs\customies\block\traits\PlacementDirectionTrait;
use customiesdevs\customies\block\traits\PlacementPositionTrait;

/**
 * Registry for block trait mappings.
 * Maps trait identifiers to their implementing classes.
 */
final class BlockTraitRegistry {

	/**
	 * Maps trait identifiers to their implementing class names.
	 * @var array<string, class-string<BlockTrait>>
	 */
	private static array $traits = [
		'minecraft:placement_direction' => PlacementDirectionTrait::class,
		'minecraft:placement_position' => PlacementPositionTrait::class,
	];

	/**
	 * Register a custom block trait.
	 *
	 * @param string $name Trait identifier (e.g., 'minecraft:placement_direction')
	 * @param class-string<BlockTrait> $class Fully qualified class name implementing BlockTrait
	 */
	public static function register(string $name, string $class): void {
		self::$traits[$name] = $class;
	}

	/**
	 * Get the class name of a trait by its identifier.
	 *
	 * @param string $name Trait identifier
	 * @return class-string<BlockTrait>|null Returns the class name, or null if not registered
	 */
	public static function get(string $name): ?string {
		return self::$traits[$name] ?? null;
	}

	/**
	 * Check if a trait is registered.
	 *
	 * @param string $name Trait identifier
	 * @return bool True if registered, false otherwise
	 */
	public static function has(string $name): bool {
		return isset(self::$traits[$name]);
	}

	/**
	 * Instantiate a block trait from JSON data.
	 *
	 * @param string $name Trait identifier
	 * @param mixed $data JSON-decoded data for the trait
	 * @return BlockTrait|null Returns the trait instance or null if not registered
	 */
	public static function fromJson(string $name, mixed $data): ?BlockTrait {
		return match($name) {
			'minecraft:placement_direction' => new PlacementDirectionTrait(
				state: $data['enabled_states'] ?? [],
				yRotationOffset: (float) ($data['y_rotation_offset'] ?? 0.0)
			),
			'minecraft:placement_position' => new PlacementPositionTrait(
				state: $data['enabled_states'] ?? []
			),
			default => null
		};
	}
}
