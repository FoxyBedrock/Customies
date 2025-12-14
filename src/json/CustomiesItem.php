<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\traits\ItemComponentsTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;

class CustomiesItem extends Item implements ItemComponents {
	use ItemComponentsTrait;

	public function __construct(array $components) {
		parent::__construct(new ItemIdentifier(ItemTypeIds::newId()));
		foreach($components as $name => $data) {
			$component = ItemComponentRegistry::fromJson($name, $data);
			if($component !== null) {
				$this->addComponent($component);
			}
		}
	}
}