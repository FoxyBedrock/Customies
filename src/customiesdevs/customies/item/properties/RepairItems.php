<?php

namespace customiesdevs\customies\item\properties;

class RepairItems {

	/**
	 * @param string[] $items Items that may be used to repair an item.
	 * @param int $repairAmount How much the item is repaired.
	 */
	public function __construct(
		private readonly array $items,
		private readonly int $repairAmount,
	) {}

	public function toArray(): array {
		$items = [];
		foreach($this->items as $item) {
			$items[] = [
				"name" => $item
			];
		}
		return [
			"items" => $items,
			"repair_amount" => $this->repairAmount
		];
	}

}