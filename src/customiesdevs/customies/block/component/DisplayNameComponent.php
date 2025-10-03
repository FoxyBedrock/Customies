<?php

namespace customiesdevs\customies\block\component;

class DisplayNameComponent implements BlockComponent {

	private string $displayName;

	/**
	 * Specifies the language file key that maps to what text will be displayed when you hover over the block in your inventory and hotbar.  
	 * If the string given can not be resolved as a loc string, the raw string given will be displayed.  
	 * If this component is omitted, the name of the block will be used as the display name.  
	 * @param string $displayName Example using String: `"Custom Block"`  
	 * Example using Localization String: `"block.customies:custom_block.name"`
	 */
	public function __construct(string $displayName) {
		$this->displayName = $displayName;
	}

	public function getName(): string {
		return VanillaBlockComponents::DISPLAY_NAME;
	}

	public function getValue(): array {
		return [
			"value" => $this->displayName
		];
	}

	public static function fromJson(mixed $data): static {
		return new self($data);
	}
}