<?php

namespace customiesdevs\customies\block;

use customiesdevs\customies\block\component\VanillaBlockComponents;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;

class CustomiesBlock extends Block implements BlockComponents {
	use BlockComponentsTrait;
	
	public function __construct(array $components) {
		parent::__construct(
			new BlockIdentifier(BlockTypeIds::newId()), 
			"Custom Block", 
			new BlockTypeInfo(new BlockBreakInfo(1))
		);
		foreach ($components as $componentName => $componentData) {
			$componentClass = VanillaBlockComponents::classFor($componentName) ?? null;
			if ($componentClass !== null && method_exists($componentClass, 'fromJson')) {
				$this->addComponent($componentClass::fromJson($componentData));
			}
		}
	}
}