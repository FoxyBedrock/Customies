<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class InteractButtonComponent implements ItemComponent {

	private string $interactButton;

	/**
	 * Ineract Button is a boolean or string that determines if the interact button is shown in touch controls, and what text is displayed on the button. When set to 'true', the default 'Use Item' text will be used.
	 * @param bool|string $interactButton
	 */
	public function __construct(bool|string $interactButton) {
		if(is_bool($interactButton) === true){
			$this->interactButton = "action.interact.use";
		}else{
			$this->interactButton = (string) $interactButton;
		}
	}

	public function getName(): string {
		return VanillaItemComponents::INTERACT_BUTTON;
	}

	public function getValue(): array {
		return [
			"interact_text" => $this->interactButton,
			"requires_interact" => 1
		];
	}

	public static function fromJson(mixed $data): static {
		return new self($data ?? "action.interact.use");
	}
}