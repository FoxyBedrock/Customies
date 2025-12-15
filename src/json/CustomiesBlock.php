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

	/**
	 * Constructs a new CustomiesBlock with the given components.
	 *
	 * Each component is provided as an associative array where the key is the
	 * component identifier and the value is JSON-decoded data.
	 *
	 * Components are automatically instantiated via BlockComponentRegistry and added
	 * to this block if they are registered.
	 *
	 * @param array<string, mixed> $components Associative array of component identifiers to data
	 * @param float|null $hardness The hardness of the block (default 1.0)
	 * @param int|null $toolType The required tool type to break the block (default BlockToolType::NONE)
	 * @param int|null $toolHarvestLevel The required tool harvest level (default 0)
	 * @param float|null $blastResistance Optional blast resistance
	 */
	public function __construct(
		array $components,
		?float $hardness = 1.0,
		?int $toolType = BlockToolType::NONE,
		?int $toolHarvestLevel = 0,
		?float $blastResistance = null
	) {
		// Construct the base Block with identifier and block info
		parent::__construct(
			new BlockIdentifier(BlockTypeIds::newId()), 
			"Custom Block", 
			new BlockTypeInfo(new BlockBreakInfo(
				$hardness,
				$toolType,
				$toolHarvestLevel,
				$blastResistance
			))
		);
		// Add all registered components
		foreach($components as $name => $data) {
			$component = BlockComponentRegistry::fromJson($name, $data);
			if($component !== null) {
				$this->addComponent($component);
			}
		}
	}
}