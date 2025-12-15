<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\json\BehaviorManager;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\SingletonTrait;

final class Customies extends PluginBase {
	use SingletonTrait {
		setInstance as private;
		reset as private;
	}

	public function onLoad(): void{
		self::setInstance($this);
	}

	/**
	 * Called when the plugin is enabled.
	 *
	 * Registers event listeners, loads and registers all behavior definitions,
	 * and schedules initialization hooks for custom blocks after the server
	 * has fully started.
	 */
	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents(new CustomiesListener(), $this);
		// Register all custom behavior JSON definitions
		BehaviorManager::getInstance()->registerAll();
		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(static function (): void {
			// This task is scheduled with a 0-tick delay so it runs as soon as the server has started.
				// Plugins should register their custom blocks and entities in onEnable()
				// before this is executed.
			CustomiesBlockFactory::getInstance()->addWorkerInitHook();
		}), 0);
	}
}