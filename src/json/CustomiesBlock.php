<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\block\component\BlockComponents;
use customiesdevs\customies\block\component\BlockComponentsTrait;
use customiesdevs\customies\block\blockpermutations\BlockPermutations;
use customiesdevs\customies\block\blockpermutations\BlockPermutationsTrait;
use customiesdevs\customies\block\states\BlockStates;
use customiesdevs\customies\block\states\BlockStatesTrait;
use customiesdevs\customies\block\traits\BlockTraits;
use customiesdevs\customies\block\traits\BlockTraitsTrait;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;

class CustomiesBlock extends Block implements BlockComponents, BlockTraits, BlockStates, BlockPermutations {
	use BlockComponentsTrait;
	use BlockTraitsTrait;
	use BlockStatesTrait;
	use BlockPermutationsTrait;

	/**
	 * Constructs a new CustomiesBlock with the given configuration.
	 *
	 * @param array<string, mixed> $components Associative array of component identifiers to data
	 * @param array<string, mixed> $traits Associative array of trait identifiers to data
	 * @param array<string, mixed> $states Associative array of state identifiers to values
	 * @param array<int, array{condition: string, components: array}> $permutations Array of permutation definitions
	 * @param float $hardness The hardness of the block (default 1.0)
	 * @param int $toolType The required tool type to break the block (default BlockToolType::NONE)
	 * @param int $toolHarvestLevel The required tool harvest level (default 0)
	 * @param float|null $blastResistance Optional blast resistance
	 */
	public function __construct(
		array $components,
		array $traits = [],
		array $states = [],
		array $permutations = [],
		float $hardness = 1.0,
		int $toolType = BlockToolType::NONE,
		int $toolHarvestLevel = 0,
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

		// Add all registered traits
		foreach($traits as $name => $data) {
			$trait = BlockTraitRegistry::fromJson($name, $data);
			if($trait !== null) {
				$this->addTrait($trait);
			}
		}

		// Add all registered states
		foreach($states as $name => $data) {
			$state = BlockStateRegistry::fromJson($name, $data);
			if($state !== null) {
				$this->addState($state);
			}
		}

		// Add all permutations
		$this->addPermutations(BlockPermutationRegistry::fromJson($permutations));
	}
}