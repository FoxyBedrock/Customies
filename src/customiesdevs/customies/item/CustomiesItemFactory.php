<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use Closure;
use customiesdevs\customies\util\NBT;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\data\bedrock\item\BlockItemIdMap;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\inventory\CreativeCategory;
use pocketmine\inventory\CreativeGroup;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\lang\Translatable;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use ReflectionClass;
use RuntimeException;

use function array_values;

final class CustomiesItemFactory {
	use SingletonTrait;

	/**
	 * @var ItemTypeEntry[]
	 */
	private array $itemTableEntries = [];
	private array $groups = [];

	/**
	 * Get a custom item from its identifier. An exception will be thrown if the item is not registered.
	 * @param string $identifier The string identifier for the item, usually in the format "namespace:item_name"
	 * @param int $amount The amount of the item to be returned
	 * @return Item The item instance
	 * @throws InvalidArgumentException if the item is not registered
	 */
	public function get(string $identifier, int $amount = 1): Item {
		$item = StringToItemParser::getInstance()->parse($identifier);
		if($item === null) {
			throw new InvalidArgumentException("Custom item " . $identifier . " is not registered");
		}
		return $item->setCount($amount);
	}

	private function loadGroups() : void {
		if($this->groups !== []){
			return;
		}
		foreach(CreativeInventory::getInstance()->getAllEntries() as $entry){
			$group = $entry->getGroup();
			if($group !== null){
				$this->groups[$group->getName()->getText()] = $group;
			}
		}
	}

	/**
	 * Returns custom item entries
	 * @return ItemTypeEntry[]
	 */
	public function getItemTableEntries(): array {
		return array_values($this->itemTableEntries);
	}

	/**
	 * Registers the item to the item factory and assigns it an ID. It also updates the required mappings and stores the
	 * item components if present.
	 * @param Closure $itemFunc A closure that returns an instance of the item to be registered
	 * @param string $identifier The string identifier for the item, usually in the format "namespace:item_name"
	 * @param CreativeInventoryInfo|null $creativeInfo The creative inventory info for the item, if any
	 * @throws InvalidArgumentException if the closure does not return an Item instance
	 */
	public function registerItem(Closure $itemFunc, string $identifier, ?CreativeInventoryInfo $creativeInfo = null): void {
		$item = $itemFunc();
		if(!$item instanceof Item) {
			throw new InvalidArgumentException("Class returned from closure is not a Item");
		}
		$itemId = $item->getTypeId();

		GlobalItemDataHandlers::getDeserializer()->map($identifier, fn() => clone $item);
		GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($identifier));

		StringToItemParser::getInstance()->register($identifier, fn() => clone $item);

		// This is where the components are added to the item
		$componentBased = $item instanceof ItemComponents;
		$nbt = $this->createItemNbt($item, $identifier, $itemId, $creativeInfo);

		if($creativeInfo !== null){
			$this->loadGroups();
			if($creativeInfo->getCategory() === CreativeInventoryInfo::CATEGORY_ALL || $creativeInfo->getCategory() === CreativeInventoryInfo::CATEGORY_COMMANDS){
				return;
			}

			$group = $this->groups[$creativeInfo->getGroup()] ?? ($creativeInfo->getGroup() !== "" && $creativeInfo->getGroup() !== CreativeInventoryInfo::NONE ? new CreativeGroup(
				new Translatable($creativeInfo->getGroup()),
				$item
			) : null);

			if($group !== null){
				$this->groups[$group->getName()->getText()] = $group;
			}

			$category = match ($creativeInfo->getCategory()) {
				CreativeInventoryInfo::CATEGORY_CONSTRUCTION => CreativeCategory::CONSTRUCTION,
				CreativeInventoryInfo::CATEGORY_ITEMS => CreativeCategory::ITEMS,
				CreativeInventoryInfo::CATEGORY_NATURE => CreativeCategory::NATURE,
				CreativeInventoryInfo::CATEGORY_EQUIPMENT => CreativeCategory::EQUIPMENT,
				default => throw new AssumptionFailedError("Unknown category")
			};

			CreativeInventory::getInstance()->add($item, $category, $group);
		}	

		$this->itemTableEntries[$identifier] = $entry = new ItemTypeEntry($identifier, $itemId, $componentBased, $componentBased ? 1 : 0, new CacheableNbt($nbt));
		$this->registerCustomItemMapping($identifier, $itemId, $entry);
	}

	/**
	 * Creates the NBT data for the item. This includes the components and their values.
	 * If the item does not have components, an empty CompoundTag is returned.
	 * @param Item $item The item for which to create the NBT data
	 * @param string $identifier The string identifier for the item, usually in the format "namespace:item_name"
	 * @param int $itemId The numerical ID to be assigned to the item
	 * @param CreativeInventoryInfo|null $creativeInfo The creative inventory info for the item, if any
	 * @return CompoundTag The NBT data for the item
	 */
	private function createItemNbt(Item $item, string $identifier, int $itemId, ?CreativeInventoryInfo $creativeInfo): CompoundTag {
		$components = CompoundTag::create();
		$properties = CompoundTag::create();

		if($item instanceof ItemComponents) {
			// Set default values for all properties first
			$properties->setTag("allow_off_hand", NBT::getTagType(false));
			$properties->setTag("can_destroy_in_creative", NBT::getTagType(true));
			$properties->setTag("damage", NBT::getTagType(0));
			$properties->setTag("enchantable_slot", NBT::getTagType("none"));
			$properties->setTag("enchantable_value", NBT::getTagType(0));
			$properties->setTag("foil", NBT::getTagType(false));
			$properties->setTag("frame_count", NBT::getTagType(1));
			$properties->setTag("hand_equipped", NBT::getTagType(false));
			$properties->setTag("liquid_clipped", NBT::getTagType(false));
			$properties->setTag("max_stack_size", NBT::getTagType(64));
			$properties->setTag("mining_speed", NBT::getTagType(1));
			$properties->setTag("should_despawn", NBT::getTagType(true));
			$properties->setTag("stacked_by_data", NBT::getTagType(false));
			$properties->setTag("use_animation", NBT::getTagType(0));
			$properties->setTag("use_duration", NBT::getTagType(0));
			
			foreach($item->getComponents() as $component) {
				$tag = NBT::getTagType($component->getValue());
				if($tag === null) {
					throw new RuntimeException("Failed to get tag type for component " . $component->getName());
				}
				
				// Override defaults with component-specific values
				switch($component->getName()) {
					case "minecraft:allow_off_hand":
						$properties->setTag("allow_off_hand", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:can_destroy_in_creative":
						$properties->setTag("can_destroy_in_creative", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:damage":
						$properties->setTag("damage", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:enchantable":
						$properties->setTag("enchantable_slot", NBT::getTagType($component->getValue()["slot"]));
						$properties->setTag("enchantable_value", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:glint":
						$properties->setTag("foil", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:hand_equipped":
						$properties->setTag("hand_equipped", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:hover_text_color":
						$properties->setTag("hover_text_color", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:liquid_clipped":
						$properties->setTag("liquid_clipped", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:max_stack_size":
						$properties->setTag("max_stack_size", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:icon":
						$properties->setTag("minecraft:icon", $tag);
						break;
					case "minecraft:should_despawn":
						$properties->setTag("should_despawn", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:stacked_by_data":
						$properties->setTag("stacked_by_data", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:use_animation":
						$properties->setTag("use_animation", NBT::getTagType($component->getValue()["value"]));
						break;
					case "minecraft:use_modifiers":
						$properties->setTag("use_duration", NBT::getTagType($component->getValue()["use_duration"]));
						break;
				}
				
				// Setting the actual component
				// The icon component is already set in item_properties, no need to set it again
				if($component->getName() !== "minecraft:icon") {
					$components->setTag($component->getName(), $tag);
				}
			}
			if($creativeInfo !== null) {
				$properties->setTag("creative_category", NBT::getTagType($creativeInfo->getNumericCategory()));
				$properties->setTag("creative_group", NBT::getTagType($creativeInfo->getGroup()));
			}
			$components->setTag("item_properties", $properties);
			return CompoundTag::create()
				->setTag("components", $components)
				->setInt("id", $itemId)
				->setString("name", $identifier);
		}
		return CompoundTag::create();
	}

	/**
	 * Registers a custom item ID to the required mappings in the global ItemTypeDictionary instance.
	 * This allows the item to be recognized and used within the game.
	 * @param string $identifier The string identifier for the item, usually in the format "namespace:item_name"
	 * @param int $itemId The numerical ID to be assigned to the item
	 * @param ItemTypeEntry $entry The ItemTypeEntry instance representing the item
	 */
	private function registerCustomItemMapping(string $identifier, int $itemId, ItemTypeEntry $entry): void {
		$dictionary = TypeConverter::getInstance()->getItemTypeDictionary();
		$reflection = new ReflectionClass($dictionary);

		$intToString = $reflection->getProperty("intToStringIdMap");
		/** @var int[] $value */
		$value = $intToString->getValue($dictionary);
		$intToString->setValue($dictionary, $value + [$itemId => $identifier]);

		$stringToInt = $reflection->getProperty("stringToIntMap");
		/** @var int[] $value */
		$value = $stringToInt->getValue($dictionary);
		$stringToInt->setValue($dictionary, $value + [$identifier => $itemId]);

		$itemTypes = $reflection->getProperty("itemTypes");
		$value = $itemTypes->getValue($dictionary);
		$value[] = $entry;
		$itemTypes->setValue($dictionary, $value);
	}

	/**
	 * Registers the required mappings for the block to become an item that can be placed etc. It is assigned an ID that
	 * correlates to its block ID.
	 * @param string $identifier The string identifier for the block item, usually in the format "namespace:block_name"
	 * @param Block $block The block instance to be registered as an item
	 */
	public function registerBlockItem(string $identifier, Block $block): void {
		$itemId = $block->getIdInfo()->getBlockTypeId();
		StringToItemParser::getInstance()->registerBlock($identifier, fn() => clone $block);
		$this->itemTableEntries[] = $entry = new ItemTypeEntry($identifier, $itemId, false, 2, new CacheableNbt(CompoundTag::create()));
		$this->registerCustomItemMapping($identifier, $itemId, $entry);

		$blockItemIdMap = BlockItemIdMap::getInstance();
		$reflection = new ReflectionClass($blockItemIdMap);

		$itemToBlockId = $reflection->getProperty("itemToBlockId");
		/** @var string[] $value */
		$value = $itemToBlockId->getValue($blockItemIdMap);
		$itemToBlockId->setValue($blockItemIdMap, $value + [$identifier => $identifier]);
	}
}
