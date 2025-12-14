<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\BlockComponents;
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
		foreach($components as $name => $data) {
			$component = BlockComponentRegistry::fromJson($name, $data);
			if($component !== null) {
				$this->addComponent($component);
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