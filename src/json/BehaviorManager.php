<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\Customies;
use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\CustomiesItemFactory;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use function array_filter;
use function is_dir;
use function is_file;
use function mkdir;
use function scandir;
use function str_ends_with;

/**
 * Manages loading and registration of custom items and blocks from JSON behavior files.
 */
final class BehaviorManager {
	use SingletonTrait;

	private string $behaviorDirectory;

	public function __construct() {
		$this->behaviorDirectory = Customies::getInstance()->getDataFolder() . "behavior/";
		$this->ensureDirectoriesExist();
	}

	/**
	 * Ensures the behavior directories exist
	 */
	private function ensureDirectoriesExist(): void {
		foreach(['items', 'blocks'] as $subdir) {
			$path = $this->behaviorDirectory . $subdir . "/";
			if(!is_dir($path)) {
				mkdir($path, 0777, true);
			}
		}
	}

	/**
	 * Gets all JSON files in a subdirectory
	 * @param string $subdir The subdirectory name ('items' or 'blocks')
	 * @return string[]
	 */
	private function getJsonFiles(string $subdir): array {
		$path = $this->behaviorDirectory . $subdir . "/";
		$files = scandir($path);
		if($files === false) {
			return [];
		}
		return array_filter($files, function($file) use ($path) {
			return $file !== '.' && 
				   $file !== '..' && 
				   is_file($path . $file) &&
				   str_ends_with($file, '.json');
		});
	}

	/**
	 * Gets a configuration from a JSON file
	 */
	private function getConfig(string $subdir, string $filename): Config {
		return new Config($this->behaviorDirectory . $subdir . "/" . $filename, Config::JSON);
	}

	/**
	 * Registers all items and blocks from behavior files
	 */
	public function registerAll(): void {
		$this->registerItems();
		$this->registerBlocks();
	}

	/**
	 * Registers all items from JSON files
	 */
	public function registerItems(): void {
		$this->registerFromDirectory('items', 'minecraft:item', function(array $config): void {
			$this->registerItem($config);
		});
	}

	/**
	 * Registers all blocks from JSON files
	 */
	public function registerBlocks(): void {
		$this->registerFromDirectory('blocks', 'minecraft:block', function(array $config): void {
			$this->registerBlock($config);
		});
	}

	/**
	 * Generic registration loop
	 */
	private function registerFromDirectory(string $subdir, string $rootKey, callable $register): void {
		$registeredCount = 0;
		$errorCount = 0;
		$type = rtrim($subdir, 's'); // 'items' -> 'item'

		foreach($this->getJsonFiles($subdir) as $file) {
			try {
				$config = $this->getConfig($subdir, $file)->getAll();
				if(!isset($config[$rootKey])) {
					throw new \InvalidArgumentException("Missing $rootKey");
				}
				$register($config[$rootKey]);
				$registeredCount++;
			} catch(\Exception $e) {
				$errorCount++;
				Customies::getInstance()->getLogger()->error(
					"Failed to register $type from '$file': " . $e->getMessage()
				);
			}
		}

		if($registeredCount > 0 || $errorCount > 0) {
			Customies::getInstance()->getLogger()->info(
				"Registered $registeredCount custom {$subdir}" . 
				($errorCount > 0 ? " ($errorCount failed)" : "")
			);
		}
	}

	/**
	 * Registers a single item from config
	 */
	private function registerItem(array $config): void {
		if(!isset($config["components"], $config["description"]["identifier"])) {
			throw new \InvalidArgumentException("Missing required fields");
		}

		$identifier = $config["description"]["identifier"];
		$components = $config["components"];
		$creativeInfo = $this->getCreativeInfo($config);

		CustomiesItemFactory::getInstance()->registerItem(
			static fn() => new CustomiesItem($components),
			$identifier,
			$creativeInfo
		);
	}

	/**
	 * Registers a single block from config
	 */
	private function registerBlock(array $config): void {
		if(!isset($config["components"], $config["description"]["identifier"])) {
			throw new \InvalidArgumentException("Missing required fields");
		}

		$identifier = $config["description"]["identifier"];
		$components = $config["components"];
		$creativeInfo = $this->getCreativeInfo($config);

		CustomiesBlockFactory::getInstance()->registerBlock(
			static fn(): Block => new CustomiesBlock($components),
			$identifier,
			$creativeInfo
		);
	}

	/**
	 * Gets creative inventory info from config
	 */
	private function getCreativeInfo(array $config): CreativeInventoryInfo {
		$category = CreativeInventoryInfo::CATEGORY_ITEMS;
		$group = CreativeInventoryInfo::NONE;

		if(isset($config["description"]["menu_category"])) {
			$menuCategory = $config["description"]["menu_category"];
			$category = $menuCategory["category"] ?? $category;
			$group = $menuCategory["group"] ?? $group;
		}

		return new CreativeInventoryInfo($category, $group);
	}
}
