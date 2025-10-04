<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\component;

/**
 * Registry of known vanilla block component identifiers and their concrete classes.
 * Utility methods allow validation, lookup and (optional) runtime overrides.
 */
final class VanillaBlockComponents {
	public const COLLISION_BOX = 'minecraft:collision_box';
	public const CRAFTING_TABLE = 'minecraft:crafting_table';
	public const DESTRUCTIBLE_BY_EXPLOSION = 'minecraft:destructible_by_explosion';
	public const DESTRUCTIBLE_BY_MINING = 'minecraft:destructible_by_mining';
	public const DESTRUCTION_PARTICLES = 'minecraft:destruction_particles';
	public const DISPLAY_NAME = 'minecraft:display_name';
	public const FLAMMABLE = 'minecraft:flammable';
	public const FRICTION = 'minecraft:friction';
	public const GEOMETRY = 'minecraft:geometry';
	public const ITEM_VISUAL = 'minecraft:item_visual';
	public const LIGHT_DAMPENING = 'minecraft:light_dampening';
	public const LIGHT_EMISSION = 'minecraft:light_emission';
	public const LIQUID_DETECTION = 'minecraft:liquid_detection';
	public const LOOT = 'minecraft:loot';
	public const MAP_COLOR = 'minecraft:map_color';
	public const MATERIAL_INSTANCES = 'minecraft:material_instances';
	public const MOVABLE = 'minecraft:movable';
	public const PLACEMENT_FILTER = 'minecraft:placement_filter';
	public const RANDOM_OFFSET = 'minecraft:random_offset';
	public const REDSTONE_CONDUCTIVITY = 'minecraft:redstone_conductivity';
	public const SELECTION_BOX = 'minecraft:selection_box';

	/** @var string[] */
	private const ALL = [
		self::COLLISION_BOX,
		self::CRAFTING_TABLE,
		self::DESTRUCTIBLE_BY_EXPLOSION,
		self::DESTRUCTIBLE_BY_MINING,
		self::DESTRUCTION_PARTICLES,
		self::DISPLAY_NAME,
		self::FLAMMABLE,
		self::FRICTION,
		self::GEOMETRY,
		self::ITEM_VISUAL,
		self::LIGHT_DAMPENING,
		self::LIGHT_EMISSION,
		self::LIQUID_DETECTION,
		self::LOOT,
		self::MAP_COLOR,
		self::MATERIAL_INSTANCES,
		self::MOVABLE,
		self::PLACEMENT_FILTER,
		self::RANDOM_OFFSET,
		self::REDSTONE_CONDUCTIVITY,
		self::SELECTION_BOX,
	];

	/**
	 * Map of component identifier => FQCN implementing BlockComponent.
	 * @var array<string, class-string<BlockComponent>>
	 */
	private static array $classMap = [
		self::COLLISION_BOX => CollisionBoxComponent::class,
		self::CRAFTING_TABLE => CraftingTableComponent::class,
		self::DESTRUCTIBLE_BY_EXPLOSION => DestructibleByExplosionComponent::class,
		self::DESTRUCTIBLE_BY_MINING => DestructibleByMiningComponent::class,
		self::DESTRUCTION_PARTICLES => DestructionParticlesComponent::class,
		self::DISPLAY_NAME => DisplayNameComponent::class,
		self::FLAMMABLE => FlammableComponent::class,
		self::FRICTION => FrictionComponent::class,
		self::GEOMETRY => GeometryComponent::class,
		self::ITEM_VISUAL => ItemVisualComponent::class,
		self::LIGHT_DAMPENING => LightDampeningComponent::class,
		self::LIGHT_EMISSION => LightEmissionComponent::class,
		self::LIQUID_DETECTION => LiquidDetectionComponent::class,
		self::LOOT => LootComponent::class,
		self::MAP_COLOR => MapColorComponent::class,
		self::MATERIAL_INSTANCES => MaterialInstancesComponent::class,
		self::MOVABLE => MovableComponent::class,
		self::PLACEMENT_FILTER => PlacementFilterComponent::class,
		self::RANDOM_OFFSET => RandomOffsetComponent::class,
		self::REDSTONE_CONDUCTIVITY => RedstoneConductivityComponent::class,
		self::SELECTION_BOX => SelectionBoxComponent::class,
	];

	private function __construct() {}

	/**
	 * Get all component identifiers.
	 * @return string[]
	 */
	public static function getAll(): array {
		return self::ALL;
	}

	/**
	 * Check if the given identifier is a known component.
	 * @param string $identifier Component identifier
	 * @return bool True if the identifier is known, false otherwise.
	 */
	public static function isValid(string $identifier): bool {
		return in_array($identifier, self::ALL, true);
	}

	/**
	 * Returns the component class for the identifier if registered, null otherwise.
	 * @param string $identifier Component identifier
	 * @return class-string<BlockComponent>|null
	 */
	public static function getClass(string $identifier): ?string {
		return self::$classMap[$identifier] ?? null;
	}

	/**
	 * Determine if a component class exists for the identifier.
	 * @param string $identifier Component identifier
	 * @return bool True if a class is registered for the identifier, false otherwise.
	 */
	public static function hasClass(string $identifier): bool {
		return isset(self::$classMap[$identifier]);
	}

	/**
	 * Register or override a component class at runtime.
	 * @param string $identifier Component identifier (must be one of the known constants)
	 * @param class-string<BlockComponent> $fqcn Fully qualified class name implementing BlockComponent
	 */
	public static function registerClass(string $identifier, string $fqcn): void {
		if (!self::isValid($identifier)) {
			throw new \InvalidArgumentException("Unknown component id: $identifier");
		}
		if (!is_subclass_of($fqcn, BlockComponent::class)) {
			throw new \InvalidArgumentException("Class $fqcn must implement " . BlockComponent::class);
		}
		self::$classMap[$identifier] = $fqcn;
	}
}