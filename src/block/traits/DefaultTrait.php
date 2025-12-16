<?php

namespace customiesdevs\customies\block\traits;

use customiesdevs\customies\block\component\DisplayNameComponent;
use customiesdevs\customies\block\component\GeometryComponent;
use customiesdevs\customies\block\component\MaterialInstancesComponent;
use customiesdevs\customies\block\properties\Material;

trait DefaultTrait {
	use BlockComponentsTrait;

	/**
	 * Initializes the default components for a block with the given texture and name.
	 * @param string $texture
	 * @param string $name
	 * @return void
	 */
	protected function initComponent(string $texture, string $name): void {
		$this->addComponent(new GeometryComponent());
		$this->addComponent(new MaterialInstancesComponent([new Material("*", $texture)]));
		$this->addComponent(new DisplayNameComponent($name));
	}
}