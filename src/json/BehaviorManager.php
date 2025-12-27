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
 *
 * Provides methods to scan the behavior folder, read JSON definitions,
 * and register items and blocks with their components.
 */
final class BehaviorManager {
	use SingletonTrait;

	/** @var string Path to the behavior folder */
	private string $behaviorDirectory;

	public function __construct() {
		$this->behaviorDirectory = Customies::getInstance()->getDataFolder() . "behavior/";
		$this->ensureDirectoriesExist();
	}

	/**
	 * Ensures that the 'items' and 'blocks' subdirectories exist.
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
	 * Gets all JSON files in a given subdirectory.
	 *
	 * @param string $subdir Subdirectory name ('items' or 'blocks')
	 * @return string[] List of JSON filenames
	 */
	private function getJsonFiles(string $subdir): array {
		$path = $this->behaviorDirectory . $subdir . "/";
		$files = scandir($path);
		if($files === false) {
			return [];
		}
		return array_filter($files, static function(string $file) use ($path): bool {
			return $file !== '.' && 
				   $file !== '..' && 
				   is_file($path . $file) &&
				   str_ends_with($file, '.json');
		});
	}

	/**
	 * Reads a JSON configuration file.
	 *
	 * @param string $subdir Subdirectory name
	 * @param string $filename JSON filename
	 * @return Config
	 */
	private function getConfig(string $subdir, string $filename): Config {
		return new Config($this->behaviorDirectory . $subdir . "/" . $filename, Config::JSON);
	}

	/**
	 * Registers all items and blocks from behavior files.
	 */
	public function registerAll(): void {
		$this->registerItems();
		$this->registerBlocks();
	}

	/**
	 * Registers all items from JSON files.
	 */
	public function registerItems(): void {
		$this->registerFromDirectory('items', 'minecraft:item', function(array $config): void {
			$this->registerItem($config);
		});
	}

	/**
	 * Registers all blocks from JSON files.
	 */
	public function registerBlocks(): void {
		$this->registerFromDirectory('blocks', 'minecraft:block', function(array $config): void {
			$this->registerBlock($config);
		});
	}

	/**
	 * Generic method for registering items or blocks from a directory.
	 *
	 * @param string $subdir Subdirectory name ('items' or 'blocks')
	 * @param string $rootKey Root key in JSON ('minecraft:item' or 'minecraft:block')
	 * @param callable(array): void $register Callback to register each entry
	 */
	private function registerFromDirectory(string $subdir, string $rootKey, callable $register): void {
		$registeredCount = 0;
		$errorCount = 0;
		$type = rtrim($subdir, 's'); // 'items' -> 'item', 'blocks' -> 'block'
		foreach($this->getJsonFiles($subdir) as $file) {
			try {
				$config = $this->getConfig($subdir, $file)->getAll();
				if(!isset($config[$rootKey])) {
					throw new \InvalidArgumentException("Missing '$rootKey' in JSON file");
				}
				$register($config[$rootKey]);
				$registeredCount++;
			}catch(\Exception $e) {
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
	 * Registers a single item from configuration.
	 *
	 * @param array<string, mixed> $config JSON-decoded item configuration
	 */
	private function registerItem(array $config): void {
		if(!isset($config["components"], $config["description"]["identifier"])) {
			throw new \InvalidArgumentException("Missing required fields 'components' or 'description.identifier'");
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
	 * Registers a single block from configuration.
	 *
	 * @param array<string, mixed> $config JSON-decoded block configuration
	 */
	private function registerBlock(array $config): void {
		if(!isset($config["description"]["identifier"])) {
			throw new \InvalidArgumentException("Missing required field 'description.identifier'");
		}
		
		$identifier = $config["description"]["identifier"];
		$components = $config["components"] ?? [];
		$creativeInfo = $this->getCreativeInfo($config);
		
		CustomiesBlockFactory::getInstance()->registerBlock(
			static fn(): Block => new CustomiesBlock($components),
			$identifier,
			$creativeInfo
		);
	}

	/**
	 * Extracts CreativeInventoryInfo from configuration.
	 *
	 * @param array<string, mixed> $config JSON-decoded configuration
	 * @return CreativeInventoryInfo
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