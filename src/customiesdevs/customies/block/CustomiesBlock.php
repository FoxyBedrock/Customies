<?php

namespace customiesdevs\customies\block;

use customiesdevs\customies\block\component\custom\BlockBreakInfoComponent;
use customiesdevs\customies\block\component\VanillaBlockComponents;
use customiesdevs\customies\block\traits\BlockComponentsTrait;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;

class CustomiesBlock extends Block implements BlockComponents {
	use BlockComponentsTrait;
	
	public function __construct(array $components) {
		foreach ($components as $componentName => $componentData) {
			$componentClass = VanillaBlockComponents::getClass($componentName) ?? null;
			if ($componentClass !== null && method_exists($componentClass, 'fromJson')) {
				$this->addComponent($componentClass::fromJson($componentData));
			}

			if($this->hasComponent("customies:block_break_info")) {
				$breakInfo = $this->getComponent("customies:block_break_info");
				if($breakInfo instanceof BlockBreakInfoComponent) {
					$hardness = $breakInfo->getValue()["hardness"];
					$toolType = $breakInfo->getValue()["tool_type"];
					$toolHarvestLevel = $breakInfo->getValue()["tool_harvest_level"];
					$blastResistance = $breakInfo->getValue()["blast_resistance"];
				}
			}
		}
		parent::__construct(
			new BlockIdentifier(BlockTypeIds::newId()), 
			"Custom Block", 
			new BlockTypeInfo(new BlockBreakInfo(
				$hardness ?? 1.0, 
				$toolType ?? BlockToolType::NONE, 
				$toolHarvestLevel ?? 0, 
				$blastResistance ?? null
			))
		);
	}
}