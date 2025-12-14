<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\component\BlockComponent;
use customiesdevs\customies\block\component\CollisionBoxComponent;
use customiesdevs\customies\block\component\CraftingTableComponent;
use customiesdevs\customies\block\component\DestructibleByExplosionComponent;
use customiesdevs\customies\block\component\DestructibleByMiningComponent;
use customiesdevs\customies\block\component\DestructionParticlesComponent;
use customiesdevs\customies\block\component\DisplayNameComponent;
use customiesdevs\customies\block\component\FlammableComponent;
use customiesdevs\customies\block\component\FrictionComponent;
use customiesdevs\customies\block\component\GeometryComponent;
use customiesdevs\customies\block\component\ItemVisualComponent;
use customiesdevs\customies\block\component\LightDampeningComponent;
use customiesdevs\customies\block\component\LightEmissionComponent;
use customiesdevs\customies\block\component\LiquidDetectionComponent;
use customiesdevs\customies\block\component\LootComponent;
use customiesdevs\customies\block\component\MapColorComponent;
use customiesdevs\customies\block\component\MaterialInstancesComponent;
use customiesdevs\customies\block\component\MovableComponent;
use customiesdevs\customies\block\component\PlacementFilterComponent;
use customiesdevs\customies\block\component\RandomOffsetComponent;
use customiesdevs\customies\block\component\RedstoneConductivityComponent;
use customiesdevs\customies\block\component\SelectionBoxComponent;

/**
 * Central registry for block component mappings.
 * Maps component identifiers to their implementing classes.
 */
final class BlockComponentRegistry {

	/** @var array<string, class-string<BlockComponent>> */
	private static array $components = [
		'minecraft:collision_box' => CollisionBoxComponent::class,
		'minecraft:crafting_table' => CraftingTableComponent::class,
		'minecraft:destructible_by_explosion' => DestructibleByExplosionComponent::class,
		'minecraft:destructible_by_mining' => DestructibleByMiningComponent::class,
		'minecraft:destruction_particles' => DestructionParticlesComponent::class,
		'minecraft:display_name' => DisplayNameComponent::class,
		'minecraft:flammable' => FlammableComponent::class,
		'minecraft:friction' => FrictionComponent::class,
		'minecraft:geometry' => GeometryComponent::class,
		'minecraft:item_visual' => ItemVisualComponent::class,
		'minecraft:light_dampening' => LightDampeningComponent::class,
		'minecraft:light_emission' => LightEmissionComponent::class,
		'minecraft:liquid_detection' => LiquidDetectionComponent::class,
		'minecraft:loot' => LootComponent::class,
		'minecraft:map_color' => MapColorComponent::class,
		'minecraft:material_instances' => MaterialInstancesComponent::class,
		'minecraft:movable' => MovableComponent::class,
		'minecraft:placement_filter' => PlacementFilterComponent::class,
		'minecraft:random_offset' => RandomOffsetComponent::class,
		'minecraft:redstone_conductivity' => RedstoneConductivityComponent::class,
		'minecraft:selection_box' => SelectionBoxComponent::class,
	];

	/**
	 * Register a custom component class.
	 * @param string $name The component identifier (e.g., 'yourplugin:custom_component')
	 * @param class-string<BlockComponent> $class The component class
	 */
	public static function register(string $name, string $class): void {
		self::$components[$name] = $class;
	}

	/**
	 * Get a component class by its identifier.
	 * @return class-string<BlockComponent>|null
	 */
	public static function get(string $name): ?string {
		return self::$components[$name] ?? null;
	}

	/**
	 * Check if a component is registered.
	 */
	public static function has(string $name): bool {
		return isset(self::$components[$name]);
	}

	/**
	 * Create a component instance from JSON data.
	 * @return BlockComponent|null Returns null if component is not registered
	 */
	public static function fromJson(string $name, mixed $data): ?BlockComponent {
		$class = self::get($name);
		if($class === null) {
			return null;
		}
		return $class::fromJson($data);
	}

	/**
	 * Get all registered component identifiers.
	 * @return string[]
	 */
	public static function getAll(): array {
		return array_keys(self::$components);
	}
}
