<?php

namespace customiesdevs\customies\item\traits;

use customiesdevs\customies\item\component\DisplayNameComponent;
use customiesdevs\customies\item\component\IconComponent;

trait DefaultTrait {
	use ItemComponentsTrait;

	protected function initComponent(string $texture, string $name): void {
		$this->addComponent(new IconComponent($texture));
		$this->addComponent(new DisplayNameComponent($name));
	}

}