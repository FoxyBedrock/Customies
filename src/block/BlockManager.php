<?php

declare(strict_types=1);

namespace customiesdevs\customies\block;

use customiesdevs\customies\Customies;
use customiesdevs\customies\item\CreativeInventoryInfo;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use function array_filter;
use function is_dir;
use function is_file;
use function mkdir;
use function scandir;

class BlockManager {
	use SingletonTrait;

	private string $blocksDirectory;

	public function __construct() {
		$this->blocksDirectory = Customies::getInstance()->getDataFolder() . "behavior/blocks/";
		$this->ensureDirectoryExists();
	}

	/**
	 * Ensures the blocks directory exists
	 */
	private function ensureDirectoryExists(): void {
		if(!is_dir($this->blocksDirectory)) {
			mkdir($this->blocksDirectory, 0777, true);
		}
	}

	/**
	 * Gets all block file names in the blocks directory
	 */
	public function getBlockFiles(): array {
		$blocksFolder = scandir($this->blocksDirectory);
		if($blocksFolder === false) {
			return [];
		}
		return array_filter($blocksFolder, function($block) {
			return $block !== '.' && 
				   $block !== '..' && 
				   is_file($this->blocksDirectory . $block) &&
				   $this->isValidBlockFile($block);
		});
	}

	/**
	 * Validates if a file is a valid block configuration file
	 * @param string $filename
	 * @return bool True if the file is a valid block config (e.g., ends with .json)
	 */
	private function isValidBlockFile(string $filename): bool {
		return str_ends_with($filename, '.json');
	}

	/**
	 * Gets the configuration for a specific block
	 * @param string $blockName The name of the block configuration file
	 * @return Config The configuration object for the block
	 */
	public function getBlockConfig(string $blockName): Config {
		$configPath = $this->blocksDirectory . $blockName;
		return new Config($configPath, Config::JSON);
	}

	/**
	 * Gets all block configurations
	 * @return Config[] An associative array of block file names to their Config objects
	 */
	public function getBlockConfigs(): array {
		$configs = [];
		foreach($this->getBlockFiles() as $blockFile) {
			$configs[$blockFile] = $this->getBlockConfig($blockFile);
		}
		return $configs;
	}

	/**
	 * Registers all blocks from configuration files
	 */
	public function registerBlocks(): void {
		$registeredCount = 0;
		$errorCount = 0;
		foreach($this->getBlockFiles() as $blockFile) {
			try {
				$this->registerBlock($blockFile);
				$registeredCount++;
			} catch(\Exception $e) {
				$errorCount++;
				Customies::getInstance()->getLogger()->error(
					"Failed to register block from '$blockFile': " . $e->getMessage()
				);
			}
		}
		Customies::getInstance()->getLogger()->info(
			"Registered $registeredCount custom blocks" . 
			($errorCount > 0 ? " ($errorCount failed)" : "")
		);
	}

	/**
	 * Registers a single block from its configuration file
     * @param string $blockFile The name of the block configuration file
     * @throws \InvalidArgumentException If the block configuration is invalid
	 */
	public function registerBlock(string $blockFile): void {
		$blockConfig = $this->getBlockConfig($blockFile)->getAll();
		
		if(!isset($blockConfig["minecraft:block"])) {
			throw new \InvalidArgumentException("Invalid block config: missing minecraft:block");
		}

		$minecraftBlock = $blockConfig["minecraft:block"];
		
		if(!isset($minecraftBlock["components"], $minecraftBlock["description"]["identifier"])) {
			throw new \InvalidArgumentException("Invalid block config: missing required fields");
		}

		$identifier = $minecraftBlock["description"]["identifier"];
		$components = $minecraftBlock["components"];

		$creativeInfo = $this->getCreativeInventoryInfo($minecraftBlock);
		
		CustomiesBlockFactory::getInstance()->registerBlock(
			static function() use ($components): Block {
				return new CustomiesBlock($components);
			},
			$identifier,
			$creativeInfo
		);
	}

	/**
	 * Gets creative inventory information from config
	 * @param array $blockConfig The block configuration array
	 * @return CreativeInventoryInfo The creative inventory info object
	 */
	private function getCreativeInventoryInfo(array $blockConfig): CreativeInventoryInfo {
		$category = CreativeInventoryInfo::CATEGORY_ITEMS;
		$group = CreativeInventoryInfo::NONE;

		if(isset($blockConfig["description"]["menu_category"])) {
			$creativeConfig = $blockConfig["description"]["menu_category"];
			$category = $creativeConfig["category"] ?? $category;
			$group = $creativeConfig["group"] ?? $group;
		}

		return new CreativeInventoryInfo($category, $group);
	}
}