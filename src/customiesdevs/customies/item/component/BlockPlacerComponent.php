<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\block\Block;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

final class BlockPlacerComponent implements ItemComponent {

	private Block $block;
	private bool $replaceBlockItem;
	private array $useOn = [];

	/**
	 * Sets the item as a Planter item component for blocks. Items with this component will place a block when used.
	 * @param Block $block
	 * @param bool $replaceBlockItem If true, the item will be registered as the item for this block.
	 */
	public function __construct(Block $block, bool $replaceBlockItem = false) {
		$this->block = $block;
		$this->replaceBlockItem = $replaceBlockItem;
	}

	public function getName(): string {
		return "minecraft:block_placer";
	}

	public function getValue(): array {
		return [
			"block" => GlobalBlockStateHandlers::getSerializer()->serialize($this->block->getStateId())->getName(),
			"replace_block_item" => $this->replaceBlockItem,
			"use_on" => $this->useOn
		];
	}

	/**
	 * TODO: Update this
	 * Add blocks to the `use_on` array in the required format.
	 * @param Block ...$blocks
	 */
	public function useOn(Block ...$blocks): self {
		foreach($blocks as $block){
			$this->useOn[] = [
				"name" => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName()
			];
		}
		return $this;
	}
}