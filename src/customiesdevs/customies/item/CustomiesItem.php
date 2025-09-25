<?php

namespace customiesdevs\customies\item;

use customiesdevs\customies\item\component\AllowOffHandComponent;
use customiesdevs\customies\item\component\BlockPlacerComponent;
use customiesdevs\customies\item\component\CanDestroyInCreativeComponent;
use customiesdevs\customies\item\component\CompostableComponent;
use customiesdevs\customies\item\component\CooldownComponent;
use customiesdevs\customies\item\component\DamageAbsorptionComponent;
use customiesdevs\customies\item\component\DamageComponent;
use customiesdevs\customies\item\component\DiggerComponent;
use customiesdevs\customies\item\component\DisplayNameComponent;
use customiesdevs\customies\item\component\DurabilityComponent;
use customiesdevs\customies\item\component\DyeableComponent;
use customiesdevs\customies\item\component\EnchantableComponent;
use customiesdevs\customies\item\component\FoodComponent;
use customiesdevs\customies\item\component\FuelComponent;
use customiesdevs\customies\item\component\GlintComponent;
use customiesdevs\customies\item\component\HandEquippedComponent;
use customiesdevs\customies\item\component\HoverTextColorComponent;
use customiesdevs\customies\item\component\IconComponent;
use customiesdevs\customies\item\component\InteractButtonComponent;
use customiesdevs\customies\item\component\LiquidClippedComponent;
use customiesdevs\customies\item\component\MaxStackSizeComponent;
use customiesdevs\customies\item\component\ProjectileComponent;
use customiesdevs\customies\item\component\RarityComponent;
use customiesdevs\customies\item\component\RecordComponent;
use customiesdevs\customies\item\component\ShooterComponent;
use customiesdevs\customies\item\component\ShouldDespawnComponent;
use customiesdevs\customies\item\component\StackedByDataComponent;
use customiesdevs\customies\item\component\ThrowableComponent;
use customiesdevs\customies\item\component\UseAnimationComponent;
use customiesdevs\customies\item\component\UseModifiersComponent;
use customiesdevs\customies\item\component\WearableComponent;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\StringToItemParser;

class CustomiesItem extends Item implements ItemComponents {

	use ItemComponentsTrait;
	
	public function __construct(array $components) {
		parent::__construct(new ItemIdentifier(ItemTypeIds::newId()));
		foreach ($components as $componentName => $componentData) {
			switch ($componentName) {
				case "minecraft:allow_off_hand":
					$this->addComponent(new AllowOffHandComponent($componentData));
					break;
				case "minecraft:block_placer":
					$this->addComponent(new BlockPlacerComponent(StringToItemParser::getInstance()->parse($componentData["block"])->getBlock()));
					break;
				case "minecraft:can_destroy_in_creative":
					$this->addComponent(new CanDestroyInCreativeComponent($componentData));
					break;
				case "minecraft:compostable":
					$this->addComponent(new CompostableComponent($componentData["composting_chance"]));
					break;
				case "minecraft:cooldown":
					$this->addComponent(new CooldownComponent(
						$componentData["category"], 
						$componentData["duration"]
					));
					break;
				case "minecraft:damage":
					$this->addComponent(new DamageComponent($componentData));
					break;
				case "minecraft:damage_absorption":
					$this->addComponent(new DamageAbsorptionComponent($componentData["absorbable_causes"]));
					break;
				case "minecraft:digger":
					$this->addComponent(new DiggerComponent(
						$componentData["use_efficiency"], 
						$componentData["destroy_speeds"] ?? []
					));
					break;
				case "minecraft:display_name":
					$this->addComponent(new DisplayNameComponent($componentData["value"]));
					break;
				case "minecraft:durability":
					$this->addComponent(new DurabilityComponent(
						$componentData["max_durability"], 
						$componentData["damage_chance"]["min"] ?? 0, 
						$componentData["damage_chance"]["max"] ?? 100
					));
					break;
				case "minecraft:dyeable":
					$this->addComponent(new DyeableComponent($componentData["default_color"]));
					break;
				case "minecraft:enchantable":
					$this->addComponent(new EnchantableComponent($componentData["slot"], $componentData["value"]));
					break;
				case "minecraft:food":
					$this->addComponent(new FoodComponent(
						$componentData["can_always_eat"], 
						$componentData["nutrition"], 
						$componentData["saturation_modifier"], 
						$componentData["using_converts_to"]
					));
					break;
				case "minecraft:fuel":
					$this->addComponent(new FuelComponent($componentData["duration"]));
					break;
				case "minecraft:glint":
					$this->addComponent(new GlintComponent($componentData));
					break;
				case "minecraft:hand_equipped":
					$this->addComponent(new HandEquippedComponent($componentData));
					break;
				case "minecraft:hover_text_color":
					$this->addComponent(new HoverTextColorComponent($componentData));
					break;
				case "minecraft:icon":
					$this->addComponent(new IconComponent(
						$componentData["textures"]["default"], 
						$componentData["textures"]["dyed"] ?? "", 
						$componentData["textures"]["icon_trim"] ?? ""
					));
					break;
				case "minecraft:interact_button":
					$this->addComponent(new InteractButtonComponent($componentData));
					break;
				case "minecraft:liquid_clipped":
					$this->addComponent(new LiquidClippedComponent($componentData));
					break;
				case "minecraft:max_stack_size":
					$this->addComponent(new MaxStackSizeComponent($componentData));
					break;
				case "minecraft:projectile":
					$this->addComponent(new ProjectileComponent(
						$componentData["minimum_critical_power"], 
						$componentData["projectile_entity"]
					));
					break;
				case "minecraft:rarity":
					$this->addComponent(new RarityComponent($componentData));
					break;
				case "minecraft:record":
					$this->addComponent(new RecordComponent(
						$componentData["comparator_signal"], 
						$componentData["duration"], 
						$componentData["sound_event"]
					));
					break;
				case "minecraft:repairable":
					// not added yet
					break;
				case "minecraft:shooter":
					$this->addComponent(new ShooterComponent(
						$componentData["ammunition"][0]["item"],
						$componentData["ammunition"][0]["use_offhand"] ?? false,
						$componentData["ammunition"][0]["search_inventory"] ?? false, 
						$componentData["ammunition"][0]["use_in_creative"] ?? false, 
						$componentData["max_draw_duration"] ?? 0.0, 
						$componentData["scale_power_by_draw_duration"] ?? false, 
						$componentData["charge_on_draw"] ?? false
					));
					break;
				case "minecraft:should_despawn":
					$this->addComponent(new ShouldDespawnComponent($componentData));
					break;
				case "minecraft:stacked_by_data":
					$this->addComponent(new StackedByDataComponent($componentData));
					break;
				case "minecraft:throwable":
					$this->addComponent(new ThrowableComponent(
						$componentData["do_swing_animation"], 
						$componentData["launch_power_scale"], 
						$componentData["max_draw_duration"],
						$componentData["max_launch_power"],
						$componentData["min_draw_duration"],
						$componentData["scale_power_by_draw_duration"]
					));
					break;
				case "minecraft:use_animation":
					$this->addComponent(new UseAnimationComponent($componentData));
					break;
				case "minecraft:use_modifiers":
					$this->addComponent(new UseModifiersComponent(
						$componentData["movement_modifier"],
						$componentData["use_duration"]
					));
					break;
				case "minecraft:wearable":
					$this->addComponent(new WearableComponent(
						$componentData["slot"],
						$componentData["protection"] ?? 0,
						$componentData["hides_player_location"] ?? false,
					));
					break;
			}
		}
	}
}