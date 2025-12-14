<?php

namespace customiesdevs\customies\json;

use customiesdevs\customies\item\component\AllowOffHandComponent;
use customiesdevs\customies\item\component\BlockPlacerComponent;
use customiesdevs\customies\item\component\BundleInteractionComponent;
use customiesdevs\customies\item\component\CanDestroyInCreativeComponent;
use customiesdevs\customies\item\component\CompostableComponent;
use customiesdevs\customies\item\component\CooldownComponent;
use customiesdevs\customies\item\component\DamageAbsorptionComponent;
use customiesdevs\customies\item\component\DamageComponent;
use customiesdevs\customies\item\component\DiggerComponent;
use customiesdevs\customies\item\component\DisplayNameComponent;
use customiesdevs\customies\item\component\DurabilityComponent;
use customiesdevs\customies\item\component\DurabilitySensorComponent;
use customiesdevs\customies\item\component\DyeableComponent;
use customiesdevs\customies\item\component\EnchantableComponent;
use customiesdevs\customies\item\component\FireResistantComponent;
use customiesdevs\customies\item\component\FoodComponent;
use customiesdevs\customies\item\component\FuelComponent;
use customiesdevs\customies\item\component\GlintComponent;
use customiesdevs\customies\item\component\HandEquippedComponent;
use customiesdevs\customies\item\component\HoverTextColorComponent;
use customiesdevs\customies\item\component\IconComponent;
use customiesdevs\customies\item\component\InteractButtonComponent;
use customiesdevs\customies\item\component\ItemComponent;
use customiesdevs\customies\item\component\LiquidClippedComponent;
use customiesdevs\customies\item\component\MaxStackSizeComponent;
use customiesdevs\customies\item\component\ProjectileComponent;
use customiesdevs\customies\item\component\RarityComponent;
use customiesdevs\customies\item\component\RecordComponent;
use customiesdevs\customies\item\component\RepairableComponent;
use customiesdevs\customies\item\component\ShooterComponent;
use customiesdevs\customies\item\component\ShouldDespawnComponent;
use customiesdevs\customies\item\component\StackedByDataComponent;
use customiesdevs\customies\item\component\StorageItemComponent;
use customiesdevs\customies\item\component\StorageWeightLimitComponent;
use customiesdevs\customies\item\component\StorageWeightModifierComponent;
use customiesdevs\customies\item\component\SwingDurationComponent;
use customiesdevs\customies\item\component\TagsComponent;
use customiesdevs\customies\item\component\ThrowableComponent;
use customiesdevs\customies\item\component\UseAnimationComponent;
use customiesdevs\customies\item\component\UseModifiersComponent;
use customiesdevs\customies\item\component\WearableComponent;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\traits\ItemComponentsTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;

class CustomiesItem extends Item implements ItemComponents {
	use ItemComponentsTrait;
	
	/**
	 * @var array<string, class-string<ItemComponent>>
	 */
	private static array $componentRegistry = [
		'minecraft:allow_off_hand' => AllowOffHandComponent::class,
		'minecraft:block_placer' => BlockPlacerComponent::class,
		'minecraft:bundle_interaction' => BundleInteractionComponent::class,
		'minecraft:can_destroy_in_creative' => CanDestroyInCreativeComponent::class,
		'minecraft:compostable' => CompostableComponent::class,
		'minecraft:cooldown' => CooldownComponent::class,
		'minecraft:damage_absorption' => DamageAbsorptionComponent::class,
		'minecraft:damage' => DamageComponent::class,
		'minecraft:digger' => DiggerComponent::class,
		'minecraft:display_name' => DisplayNameComponent::class,
		'minecraft:durability' => DurabilityComponent::class,
		'minecraft:durability_sensor' => DurabilitySensorComponent::class,
		'minecraft:dyeable' => DyeableComponent::class,
		'minecraft:enchantable' => EnchantableComponent::class,
		'minecraft:fire_resistant' => FireResistantComponent::class,
		'minecraft:food' => FoodComponent::class,
		'minecraft:fuel' => FuelComponent::class,
		'minecraft:glint' => GlintComponent::class,
		'minecraft:hand_equipped' => HandEquippedComponent::class,
		'minecraft:hover_text_color' => HoverTextColorComponent::class,
		'minecraft:icon' => IconComponent::class,
		'minecraft:interact_button' => InteractButtonComponent::class,
		'minecraft:liquid_clipped' => LiquidClippedComponent::class,
		'minecraft:max_stack_size' => MaxStackSizeComponent::class,
		'minecraft:projectile' => ProjectileComponent::class,
		'minecraft:rarity' => RarityComponent::class,
		'minecraft:record' => RecordComponent::class,
		'minecraft:repairable' => RepairableComponent::class,
		'minecraft:shooter' => ShooterComponent::class,
		'minecraft:should_despawn' => ShouldDespawnComponent::class,
		'minecraft:stacked_by_data' => StackedByDataComponent::class,
		'minecraft:storage_item' => StorageItemComponent::class,
		'minecraft:storage_weight_limit' => StorageWeightLimitComponent::class,
		'minecraft:storage_weight_modifier' => StorageWeightModifierComponent::class,
		'minecraft:swing_duration' => SwingDurationComponent::class,
		'minecraft:tags' => TagsComponent::class,
		'minecraft:throwable' => ThrowableComponent::class,
		'minecraft:use_animation' => UseAnimationComponent::class,
		'minecraft:use_modifiers' => UseModifiersComponent::class,
		'minecraft:wearable' => WearableComponent::class,
	];
	
	public function __construct(array $components) {
		parent::__construct(new ItemIdentifier(ItemTypeIds::newId()));
		foreach ($components as $componentName => $componentData) {
			$componentClass = self::$componentRegistry[$componentName] ?? null;
			if ($componentClass !== null && method_exists($componentClass, 'fromJson')) {
				$this->addComponent($componentClass::fromJson($componentData));
			}
		}
	}
}