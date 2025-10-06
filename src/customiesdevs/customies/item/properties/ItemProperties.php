<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\properties;

use customiesdevs\customies\item\component\ItemComponent;
use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\util\NBT;
use pocketmine\nbt\tag\CompoundTag;
use RuntimeException;

/**
 * ItemProperties is a small helper/builder that encapsulates:
 * - default item_properties values
 * - component-to-property overrides
 * - validation of component payload shapes
 * - building the final components CompoundTag (including item_properties)
 * - tags handling
 */
final class ItemProperties {
	/**
	 * Default property values placed under item_properties
	 */
	private const DEFAULTS = [
		'allow_off_hand' => false,
		'can_destroy_in_creative' => true,
		'damage' => 0,
		'enchantable_slot' => 'none',
		'enchantable_value' => 0,
		'foil' => false,
		'frame_count' => 1,
		'hand_equipped' => false,
		'liquid_clipped' => false,
		'max_stack_size' => 64,
		'mining_speed' => 1,
		'should_despawn' => true,
		'stacked_by_data' => false,
		'use_animation' => 0,
		'use_duration' => 0,
	];

	/**
	 * Declarative mapping for components that override item_properties
	 * componentName => [ propertyName => keyInsideComponentValue ]
	 */
	private const MAPPINGS = [
		'minecraft:allow_off_hand'      => ['allow_off_hand' => 'value'],
		'minecraft:can_destroy_in_creative' => ['can_destroy_in_creative' => 'value'],
		'minecraft:damage'              => ['damage' => 'value'],
		'minecraft:enchantable'         => ['enchantable_slot' => 'slot', 'enchantable_value' => 'value'],
		'minecraft:glint'               => ['foil' => 'value'],
		'minecraft:hand_equipped'       => ['hand_equipped' => 'value'],
		'minecraft:hover_text_color'    => ['hover_text_color' => 'value'],
		'minecraft:liquid_clipped'      => ['liquid_clipped' => 'value'],
		'minecraft:max_stack_size'      => ['max_stack_size' => 'value'],
		'minecraft:should_despawn'      => ['should_despawn' => 'value'],
		'minecraft:stacked_by_data'     => ['stacked_by_data' => 'value'],
		'minecraft:use_animation'       => ['use_animation' => 'value'],
		'minecraft:use_modifiers'       => ['use_duration' => 'use_duration'],
	];

	private CompoundTag $properties;
	private array $tags;
	private CompoundTag $components;

	public function __construct() {
		$this->properties = CompoundTag::create();
		$this->tags = [];
		$this->components = CompoundTag::create();

		// Initialize defaults
		foreach(self::DEFAULTS as $name => $default) {
			$this->properties->setTag($name, NBT::getTagType($default));
		}
	}

	/**
	 * Applies a list of components, updating both item_properties and the components tag.
	 * The raw component is always added to components (except for minecraft:icon which belongs under item_properties).
	 * @param ItemComponent[] $components
	 */
	public function applyComponents(array $components): void {
		foreach($components as $component) {
			$name = $component->getName();
			$value = $component->getValue();

			$tag = NBT::getTagType($value);
			if($tag === null) {
				throw new RuntimeException("Failed to get tag type for component {$name}");
			}

			if($name === 'minecraft:icon') {
				// icon is stored under item_properties
				$this->properties->setTag('minecraft:icon', $tag);
				continue;
			}

			if($name === 'minecraft:tags') {
				$this->tags = $value["tags"] ?? [];
			}

			if(isset(self::MAPPINGS[$name])) {
				foreach(self::MAPPINGS[$name] as $prop => $key) {
					if(!is_array($value) || !array_key_exists($key, $value)) {
						throw new RuntimeException("Missing required key '{$key}' in component {$name}");
					}
					$this->properties->setTag($prop, NBT::getTagType($value[$key]));
				}
			}

			// Always record the raw component (except icon, handled above)
			$this->components->setTag($name, $tag);
		}
	}

	/**
	 * Sets the creative category and group tags based on the provided CreativeInventoryInfo.
	 * @param CreativeInventoryInfo|null $creativeInfo The creative inventory info, or null to skip setting
	 */
	public function setCreativeInfo(?CreativeInventoryInfo $creativeInfo): void {
		if($creativeInfo !== null) {
			$this->properties->setTag('creative_category', NBT::getTagType($creativeInfo->getNumericCategory()));
			$this->properties->setTag('creative_group', NBT::getTagType($creativeInfo->getGroup()));
		}
	}

	/**
	 * Build a CompoundTag containing both item_properties and the accumulated component tags.
	 * @return CompoundTag The combined components tag
	 */
	public function buildComponentsTag(): CompoundTag {
		return CompoundTag::create()
			->setTag('item_properties', $this->properties)
			->setTag('item_tags', NBT::getTagType($this->tags))
			->merge($this->components);
	}
}
