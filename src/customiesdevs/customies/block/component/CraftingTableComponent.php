<?php

namespace customiesdevs\customies\block\component;

class CraftingTableComponent implements BlockComponent {

	private array $craftingTags;
	private string $tableName;

	/**
	 * Makes your block into a custom crafting table which enables the crafting table UI and the ability to craft recipes. This component supports only "recipe_shaped" and "recipe_shapeless" typed recipes and not others like "recipe_furnace" or "recipe_brewing_mix". If there are two recipes for one item, the recipe book will pick the first that was parsed. If two input recipes are the same, crafting may assert and the resulting item may vary.
	 * @param string $tableName Specifies the language file key that maps to what text will be displayed in the UI of this table. If the string given can not be resolved as a loc string, the raw string given will be displayed. If this field is omitted, the name displayed will default to the name specified in the "display_name" component. If this block has no "display_name" component, the name displayed will default to the name of the block.
	 * @param array $craftingTags Defines the tags recipes should define to be crafted on this table. Limited to 64 tags. Each tag is limited to 64 characters.
	 */
	public function __construct(string $tableName = "Crafting Table", array $craftingTags = ["crafting_table"]) {
		$this->craftingTags = $craftingTags;
		$this->tableName = $tableName;
	}

	public function getName(): string {
		return VanillaBlockComponents::CRAFTING_TABLE;
	}

	public function getValue(): array {
		return [
			"crafting_tags" => $this->craftingTags,
			"table_name" => $this->tableName,
			"grid_size" => 3
		];
	}

	public static function fromJson(mixed $data): static {
		return new self(
			$data["table_name"] ?? "Crafting Table",
			$data["crafting_tags"] ?? ["crafting_table"]
		);
	}
}