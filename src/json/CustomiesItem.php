<?php
declare(strict_types=1);

namespace customiesdevs\customies\json;

use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;

class CustomiesItem extends Item implements ItemComponents {
	use ItemComponentsTrait;

	/**
	 * Constructs a new CustomiesItem with the given components.
	 *
	 * Each component is provided as an associative array where the key is the
	 * component identifier and the value is JSON-decoded data.
	 *
	 * Components are automatically instantiated via ItemComponentRegistry and added
	 * to this item if they are registered.
	 *
	 * @param array<string, mixed> $components Associative array of component identifiers to data
	 */
	public function __construct(array $components) {
		// Create a new item with a unique identifier
		parent::__construct(new ItemIdentifier(ItemTypeIds::newId()));
		// Add all registered components
		foreach($components as $name => $data) {
			$component = ItemComponentRegistry::fromJson($name, $data);
			if($component !== null) {
				$this->addComponent($component);
			}
		}
	}
}