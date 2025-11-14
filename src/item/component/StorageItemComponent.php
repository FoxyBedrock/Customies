<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class StorageItemComponent implements ItemComponent {

	private bool $allowNestedStorageItems;
	private array $allowedItems;
	private array $bannedItems;
	private int $maxSlots;
	private int $maxWeightLimit;
	private int $weightInStorageItem;

	/**
	 * Enables an item to store data of the dynamic container associated with it. 
	 * A dynamic container is a container for storing items that is linked to an item instead of a block or an entity.
	 * @param bool $allowNestedStorageItems Determines whether another Storage Item is allowed inside of this item. Default is true.
	 * @param array $allowedItems List of items that are exclusively allowed in this Storage Item. If empty all items are allowed.
	 * @param array $bannedItems List of items that are not allowed in this Storage Item.
	 * @param int $maxSlots The maximum allowed weight of the sum of all contained items. Maximum is 64. Default is 64. Value must be >= 0.
	 * @param int $maxWeightLimit The maximum weight limit for the storage item. Maximum is 64. Default is 64. Value must be >= 0.
	 * @param int $weightInStorageItem The weight that the storage item itself contributes to the total weight of the items it contains. Value must be >= 0.
	 */
	public function __construct(
		bool $allowNestedStorageItems = true, 
		array $allowedItems = [], 
		array $bannedItems = [], 
		int $maxSlots = 64, 
		int $maxWeightLimit = 64, 
		int $weightInStorageItem = 4
	) {
		$this->allowNestedStorageItems = $allowNestedStorageItems;
		$this->allowedItems = $allowedItems;
		$this->bannedItems = $bannedItems;
		$this->maxSlots = $maxSlots;
		$this->maxWeightLimit = $maxWeightLimit;
		$this->weightInStorageItem = $weightInStorageItem;
	}

	public function getName(): string {
		return 'minecraft:storage_item';
	}

	public function getValue(): array {
		return [
			"allow_nested_storage_items" => $this->allowNestedStorageItems,
			"allowed_items" => $this->allowedItems,
			"banned_items" => $this->bannedItems,
			"max_slots" => $this->maxSlots,
			"max_weight_limit" => $this->maxWeightLimit,
			"weight_in_storage_item" => $this->weightInStorageItem
		];
	}

	public static function fromJson(mixed $data): static {
		return new self(
			$data["allow_nested_storage_items"] ?? true,
			$data["allowed_items"] ?? [],
			$data["banned_items"] ?? [],
			$data["max_slots"] ?? 64,
			$data["max_weight_limit"] ?? 64,
			$data["weight_in_storage_item"] ?? 4
		);
	}
}