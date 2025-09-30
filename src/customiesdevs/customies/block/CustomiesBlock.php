<?php

namespace customiesdevs\customies\block;

use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeIds;

class CustomiesBlock extends Block implements BlockComponents {

    use BlockComponentsTrait;
	
	public function __construct(array $components) {
		parent::__construct(new BlockIdentifier(BlockTypeIds::newId()));
		foreach ($components as $componentName => $componentData) {
			switch ($componentName) {
				
			}
		}
	}
}