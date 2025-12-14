<?php

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\BlockComponents;
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
use customiesdevs\customies\block\traits\BlockComponentsTrait;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;

class CustomiesBlock extends Block implements BlockComponents {
	use BlockComponentsTrait;
	
	/**
	 * @var array<string, class-string<BlockComponent>>
	 */
	private static array $componentRegistry = [
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
	
	public function __construct(array $components) {
		foreach ($components as $componentName => $componentData) {
			$componentClass = self::$componentRegistry[$componentName] ?? null;
			if ($componentClass !== null && method_exists($componentClass, 'fromJson')) {
				$this->addComponent($componentClass::fromJson($componentData));
			}
		}
		parent::__construct(
			new BlockIdentifier(BlockTypeIds::newId()), 
			"Custom Block", 
			new BlockTypeInfo(new BlockBreakInfo(
				$hardness ?? 1.0, 
				$toolType ?? BlockToolType::NONE, 
				$toolHarvestLevel ?? 0, 
				$blastResistance ?? null
			))
		);
	}
}