<?php

declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\Customies;
use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\CustomiesItemFactory;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use function array_filter;
use function is_dir;
use function is_file;
use function mkdir;
use function scandir;

class ItemManager {
	use SingletonTrait;

	private string $itemsDirectory;

	public function __construct() {
		$this->itemsDirectory = Customies::getInstance()->getDataFolder() . "behavior/items/";
		$this->ensureDirectoryExists();
	}

	/**
	 * Ensures the items directory exists
	 */
	private function ensureDirectoryExists(): void {
		if(!is_dir($this->itemsDirectory)) {
			mkdir($this->itemsDirectory, 0777, true);
		}
	}

	/**
	 * Gets all item file names in the items directory
	 */
	public function getItemFiles(): array {
		$itemsFolder = scandir($this->itemsDirectory);
		if($itemsFolder === false) {
			return [];
		}
		return array_filter($itemsFolder, function($item) {
			return $item !== '.' && 
				   $item !== '..' && 
				   is_file($this->itemsDirectory . $item) &&
				   $this->isValidItemFile($item);
		});
	}

	/**
	 * Validates if a file is a valid item configuration file
	 * @param string $filename
	 * @return bool True if the file is a valid item config (e.g., ends with .json)
	 */
	private function isValidItemFile(string $filename): bool {
		return str_ends_with($filename, '.json');
	}

	/**
	 * Gets the configuration for a specific item
	 * @param string $itemName The name of the item configuration file
	 * @return Config The configuration object for the item
	 */
	public function getItemConfig(string $itemName): Config {
		$configPath = $this->itemsDirectory . $itemName;
		return new Config($configPath, Config::JSON);
	}

	/**
	 * Gets all item configurations
	 * @return Config[] An associative array of item file names to their Config objects
	 */
	public function getItemConfigs(): array {
		$configs = [];
		foreach($this->getItemFiles() as $itemFile) {
			$configs[$itemFile] = $this->getItemConfig($itemFile);
		}
		return $configs;
	}

	/**
	 * Registers all items from configuration files
	 */
	public function registerItems(): void {
		$registeredCount = 0;
		$errorCount = 0;
		foreach($this->getItemFiles() as $itemFile) {
			try {
				$this->registerItem($itemFile);
				$registeredCount++;
			} catch(\Exception $e) {
				$errorCount++;
				Customies::getInstance()->getLogger()->error(
					"Failed to register item from '$itemFile': " . $e->getMessage()
				);
			}
		}
		Customies::getInstance()->getLogger()->info(
			"Registered $registeredCount custom items" . 
			($errorCount > 0 ? " ($errorCount failed)" : "")
		);
	}

	/**
	 * Registers a single item from its configuration file
     * @param string $itemFile The name of the item configuration file
     * @throws \InvalidArgumentException If the item configuration is invalid
	 */
	public function registerItem(string $itemFile): void {
		$itemConfig = $this->getItemConfig($itemFile)->getAll();
		
		if(!isset($itemConfig["minecraft:item"])) {
			throw new \InvalidArgumentException("Invalid item config: missing minecraft:item");
		}

		$minecraftItem = $itemConfig["minecraft:item"];
		
		if(!isset($minecraftItem["components"], $minecraftItem["description"]["identifier"])) {
			throw new \InvalidArgumentException("Invalid item config: missing required fields");
		}

		$customItem = new CustomiesItem($minecraftItem["components"]);
		$identifier = $minecraftItem["description"]["identifier"];

		$creativeInfo = $this->getCreativeInventoryInfo($minecraftItem);

		CustomiesItemFactory::getInstance()->registerItem(
			static fn() => $customItem,
			$identifier,
			$creativeInfo
		);
	}

	/**
	 * Gets creative inventory information from config
	 * @param array $itemConfig The item configuration array
	 * @return CreativeInventoryInfo The creative inventory info object
	 */
	private function getCreativeInventoryInfo(array $itemConfig): CreativeInventoryInfo {
		$category = CreativeInventoryInfo::CATEGORY_ITEMS;
		$group = CreativeInventoryInfo::NONE;

		if(isset($itemConfig["description"]["menu_category"])) {
			$creativeConfig = $itemConfig["description"]["menu_category"];
			$category = $creativeConfig["category"] ?? $category;
			$group = $creativeConfig["group"] ?? $group;
		}

		return new CreativeInventoryInfo($category, $group);
	}
}