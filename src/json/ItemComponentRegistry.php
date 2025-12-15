<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\item\component\AllowOffHandComponent;
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
use customiesdevs\customies\item\component\KineticWeaponComponent;
use customiesdevs\customies\item\component\LiquidClippedComponent;
use customiesdevs\customies\item\component\MaxStackSizeComponent;
use customiesdevs\customies\item\component\PiercingWeaponComponent;
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
use customiesdevs\customies\item\component\SwingSoundsComponent;
use customiesdevs\customies\item\component\TagsComponent;
use customiesdevs\customies\item\component\ThrowableComponent;
use customiesdevs\customies\item\component\UseAnimationComponent;
use customiesdevs\customies\item\component\UseModifiersComponent;
use customiesdevs\customies\item\component\WearableComponent;

/**
 * Central registry for item component mappings.
 * Maps component identifiers to their implementing classes.
 */
final class ItemComponentRegistry {

	/**
	 * Maps component identifiers to their implementing class names.
	 * @var array<string, class-string<ItemComponent>>
	 */
	private static array $components = [
		'minecraft:allow_off_hand' => AllowOffHandComponent::class,
		'minecraft:bundle_interaction' => BundleInteractionComponent::class,
		'minecraft:can_destroy_in_creative' => CanDestroyInCreativeComponent::class,
		'minecraft:compostable' => CompostableComponent::class,
		'minecraft:cooldown' => CooldownComponent::class,
		'minecraft:damage_absorption' => DamageAbsorptionComponent::class,
		'minecraft:damage' => DamageComponent::class,
		'minecraft:digger' => DiggerComponent::class,
		'minecraft:display_name' => DisplayNameComponent::class,
		'minecraft:durability_sensor' => DurabilitySensorComponent::class,
		'minecraft:durability' => DurabilityComponent::class,
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
		'minecraft:kinetic_weapon' => KineticWeaponComponent::class,
		'minecraft:liquid_clipped' => LiquidClippedComponent::class,
		'minecraft:max_stack_size' => MaxStackSizeComponent::class,
		'minecraft:piercing_weapon' => PiercingWeaponComponent::class,
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
		'minecraft:swing_sounds' => SwingSoundsComponent::class,
		'minecraft:tags' => TagsComponent::class,
		'minecraft:throwable' => ThrowableComponent::class,
		'minecraft:use_animation' => UseAnimationComponent::class,
		'minecraft:use_modifiers' => UseModifiersComponent::class,
		'minecraft:wearable' => WearableComponent::class,
	];

	/**
	 * Register a new custom item component.
	 *
	 * @param string $name Component identifier (e.g., 'yourplugin:custom_effect')
	 * @param class-string<ItemComponent> $class Fully qualified class name implementing ItemComponent
	 */
	public static function register(string $name, string $class): void {
		self::$components[$name] = $class;
	}

	/**
	 * Retrieve a component class by its identifier.
	 *
	 * @param string $name Component identifier
	 * @return class-string<ItemComponent>|null Returns the class name or null if not registered
	 */
	public static function get(string $name): ?string {
		return self::$components[$name] ?? null;
	}

	/**
	 * Check whether a component is registered.
	 *
	 * @param string $name Component identifier
	 * @return bool True if the component is registered, false otherwise
	 */
	public static function has(string $name): bool {
		return isset(self::$components[$name]);
	}

	/**
	 * Create a component instance from JSON data.
	 *
	 * @param string $name Component identifier
	 * @param mixed $data JSON-decoded data for the component
	 * @return ItemComponent|null Returns the component instance or null if the component is not registered
	 */
	public static function fromJson(string $name, mixed $data): ?ItemComponent {
		$class = self::get($name);
		if($class === null) return null;
		return $class::fromJson($data);
	}

	/**
	 * Get a list of all registered component identifiers.
	 *
	 * @return string[] List of component IDs
	 */
	public static function getAll(): array {
		return array_keys(self::$components);
	}
}