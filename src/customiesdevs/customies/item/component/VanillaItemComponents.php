<?php

namespace customiesdevs\customies\item\component;

final class VanillaItemComponents {
	public const ALLOW_OFF_HAND = 'minecraft:allow_off_hand';
	public const BLOCK_PLACER = 'minecraft:block_placer';
	public const BUNDLE_INTERACTION = 'minecraft:bundle_interaction';
	public const CAN_DESTROY_IN_CREATIVE = 'minecraft:can_destroy_in_creative';
	public const COMPOSTABLE = 'minecraft:compostable';
	public const COOLDOWN = 'minecraft:cooldown';
	public const DAMAGE_ABSORPTION = 'minecraft:damage_absorption';
	public const DAMAGE = 'minecraft:damage';
	public const DIGGER = 'minecraft:digger';
	public const DISPLAY_NAME = 'minecraft:display_name';
	public const DURABILITY = 'minecraft:durability';
	public const DURABILITY_SENSOR = 'minecraft:durability_sensor';
	public const DYEABLE = 'minecraft:dyeable';
	public const ENCHANTABLE = 'minecraft:enchantable';
	public const FIRE_RESISTANT = 'minecraft:fire_resistant';
	public const FOOD = 'minecraft:food';
	public const FUEL = 'minecraft:fuel';
	public const GLINT = 'minecraft:glint';
	public const HAND_EQUIPPED = 'minecraft:hand_equipped';
	public const HOVER_TEXT_COLOR = 'minecraft:hover_text_color';
	public const ICON = 'minecraft:icon';
	public const INTERACT_BUTTON = 'minecraft:interact_button';
	public const LIQUID_CLIPPED = 'minecraft:liquid_clipped';
	public const MAX_STACK_SIZE = 'minecraft:max_stack_size';
	public const PROJECTILE = 'minecraft:projectile';
	public const RARITY = 'minecraft:rarity';
	public const RECORD = 'minecraft:record';
	public const REPAIRABLE = 'minecraft:repairable';
	public const SHOOTER = 'minecraft:shooter';
	public const SHOULD_DESPAWN = 'minecraft:should_despawn';
	public const STACKED_BY_DATA = 'minecraft:stacked_by_data';
	public const STORAGE_ITEM = 'minecraft:storage_item';
	public const STORAGE_WEIGHT_LIMIT = 'minecraft:storage_weight_limit';
	public const STORAGE_WEIGHT_MODIFIER = 'minecraft:storage_weight_modifier';
	public const SWING_DURATION = 'minecraft:swing_duration';
	public const TAGS = 'minecraft:tags';
	public const THROWABLE = 'minecraft:throwable';
	public const USE_ANIMATION = 'minecraft:use_animation';
	public const USE_MODIFIERS = 'minecraft:use_modifiers';
	public const WEARABLE = 'minecraft:wearable';

	/** @var string[] */
	private const ALL = [
		self::ALLOW_OFF_HAND,
		self::BLOCK_PLACER,
		self::BUNDLE_INTERACTION,
		self::CAN_DESTROY_IN_CREATIVE,
		self::COMPOSTABLE,
		self::COOLDOWN,
		self::DAMAGE_ABSORPTION,
		self::DAMAGE,
		self::DIGGER,
		self::DISPLAY_NAME,
		self::DURABILITY,
		self::DURABILITY_SENSOR,
		self::DYEABLE,
		self::ENCHANTABLE,
		self::FIRE_RESISTANT,
		self::FOOD,
		self::FUEL,
		self::GLINT,
		self::HAND_EQUIPPED,
		self::HOVER_TEXT_COLOR,
		self::ICON,
		self::INTERACT_BUTTON,
		self::LIQUID_CLIPPED,
		self::MAX_STACK_SIZE,
		self::PROJECTILE,
		self::RARITY,
		self::RECORD,
		self::REPAIRABLE,
		self::SHOOTER,
		self::SHOULD_DESPAWN,
		self::STACKED_BY_DATA,
		self::STORAGE_ITEM,
		self::STORAGE_WEIGHT_LIMIT,
		self::STORAGE_WEIGHT_MODIFIER,
		self::SWING_DURATION,
		self::TAGS,
		self::THROWABLE,
		self::USE_ANIMATION,
		self::USE_MODIFIERS,
		self::WEARABLE,
	];

	/**
	 * Map of component identifier => FQCN implementing ItemComponent.
	 * @var array<string, class-string<ItemComponent>>
	 */
	private static array $classMap = [
		self::ALLOW_OFF_HAND => AllowOffHandComponent::class,
		self::BLOCK_PLACER => BlockPlacerComponent::class,
		self::BUNDLE_INTERACTION => BundleInteractionComponent::class,
		self::CAN_DESTROY_IN_CREATIVE => CanDestroyInCreativeComponent::class,
		self::COMPOSTABLE => CompostableComponent::class,
		self::COOLDOWN => CooldownComponent::class,
		self::DAMAGE_ABSORPTION => DamageAbsorptionComponent::class,
		self::DAMAGE => DamageComponent::class,
		self::DIGGER => DiggerComponent::class,
		self::DISPLAY_NAME => DisplayNameComponent::class,
		self::DURABILITY => DurabilityComponent::class,
		self::DURABILITY_SENSOR => DurabilitySensorComponent::class,
		self::DYEABLE => DyeableComponent::class,
		self::ENCHANTABLE => EnchantableComponent::class,
		self::FIRE_RESISTANT => FireResistantComponent::class,
		self::FOOD => FoodComponent::class,
		self::FUEL => FuelComponent::class,
		self::GLINT => GlintComponent::class,
		self::HAND_EQUIPPED => HandEquippedComponent::class,
		self::HOVER_TEXT_COLOR => HoverTextColorComponent::class,
		self::ICON => IconComponent::class,
		self::INTERACT_BUTTON => InteractButtonComponent::class,
		self::LIQUID_CLIPPED => LiquidClippedComponent::class,
		self::MAX_STACK_SIZE => MaxStackSizeComponent::class,
		self::PROJECTILE => ProjectileComponent::class,
		self::RARITY => RarityComponent::class,
		self::RECORD => RecordComponent::class,
		self::REPAIRABLE => RepairableComponent::class,
		self::SHOOTER => ShooterComponent::class,
		self::SHOULD_DESPAWN => ShouldDespawnComponent::class,
		self::STACKED_BY_DATA => StackedByDataComponent::class,
		self::STORAGE_ITEM => StorageItemComponent::class,
		self::STORAGE_WEIGHT_LIMIT => StorageWeightLimitComponent::class,
		self::STORAGE_WEIGHT_MODIFIER => StorageWeightModifierComponent::class,
		self::SWING_DURATION => SwingDurationComponent::class,
		self::TAGS => TagsComponent::class,
		self::THROWABLE => ThrowableComponent::class,
		self::USE_ANIMATION => UseAnimationComponent::class,
		self::USE_MODIFIERS => UseModifiersComponent::class,
		self::WEARABLE => WearableComponent::class,
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
		return in_array($identifier, self::ALL, true) || isset(self::$classMap[$identifier]);
	}

	/**
	 * Returns the component class for the identifier if registered, null otherwise.
	 * @param string $identifier Component identifier
	 * @return class-string<ItemComponent>|null
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
	 * @param class-string<ItemComponent> $fqcn Fully qualified class name implementing ItemComponent
	 */
	public static function registerClass(string $identifier, string $fqcn): void {
		if (!self::isValid($identifier)) {
			throw new \InvalidArgumentException("Unknown component id: $identifier");
		}
		if (!is_subclass_of($fqcn, ItemComponent::class)) {
			throw new \InvalidArgumentException("Class $fqcn must implement " . ItemComponent::class);
		}
		self::$classMap[$identifier] = $fqcn;
	}

	/**
	 * Register a custom (non-vanilla) component identifier to a class at runtime.
	 * This allows plugins to introduce new components beyond the built-in list.
	 *
	 * Rules:
	 * - Identifier must follow a simple namespaced pattern (e.g. "myplugin:my_component")
	 * - Identifier must not be one of the known vanilla identifiers (use registerClass() to override those)
	 * - Class must implement ItemComponent
	 *
	 * @param string $identifier Custom component identifier
	 * @param class-string<ItemComponent> $fqcn Fully qualified class name implementing ItemComponent
	 */
	public static function registerCustomComponent(string $identifier, string $fqcn): void {
		// Basic identifier format check: namespace:name (lowercase, digits, underscore, dash, dot, slash)
		if(!preg_match('/^[a-z0-9_\-\.]+:[a-z0-9_\-\.\/]+$/', $identifier)) {
			throw new \InvalidArgumentException("Invalid component identifier format: $identifier");
		}
		// Reserve minecraft namespace for vanilla identifiers
		if(str_starts_with($identifier, 'minecraft:')) {
			throw new \InvalidArgumentException("The namespace 'minecraft' is reserved for vanilla component identifiers. Use registerClass() to override existing ones.");
		}
		if (!is_subclass_of($fqcn, ItemComponent::class)) {
			throw new \InvalidArgumentException("Class $fqcn must implement " . ItemComponent::class);
		}
		// Register or override existing custom mapping
		self::$classMap[$identifier] = $fqcn;
	}
}