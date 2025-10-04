<?php

namespace customiesdevs\customies\item;

use customiesdevs\customies\item\component\VanillaItemComponents;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;

class CustomiesItem extends Item implements ItemComponents {
	use ItemComponentsTrait;
	
	public function __construct(array $components) {
		parent::__construct(new ItemIdentifier(ItemTypeIds::newId()));
		foreach ($components as $componentName => $componentData) {
			$componentClass = VanillaItemComponents::getClass($componentName) ?? null;
			if ($componentClass !== null && method_exists($componentClass, 'fromJson')) {
				$this->addComponent($componentClass::fromJson($componentData));
			}
		}
	}
}