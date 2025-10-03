<?php

namespace customiesdevs\customies\block\component;

class RedstoneConductivityComponent implements BlockComponent {

	private bool $allowsWireToStepDown;
	private bool $redstoneConductor;

	/**
	 * The basic redstone properties of a block; if the component is not provided the default values are used.
	 * @param bool $allowsWireToStepDown Specifies if redstone wire can stair-step downward on the block.
	 * @param bool $redstoneConductor Specifies if the block can be powered by redstone.
	 */
	public function __construct(bool $allowsWireToStepDown = true, bool $redstoneConductor = false) {
		$this->allowsWireToStepDown = $allowsWireToStepDown;
		$this->redstoneConductor = $redstoneConductor;
	}

	public function getName(): string {
		return VanillaBlockComponents::REDSTONE_CONDUCTIVITY;
	}

	public function getValue(): array {
		return [
			"allows_wire_to_step_down" => $this->allowsWireToStepDown,
			"redstone_conductor" => $this->redstoneConductor
		];
	}

	public static function fromJson(mixed $data): static {
		return new self(
			$data["allows_wire_to_step_down"] ?? true,
			$data["redstone_conductor"] ?? false
		);
	}
}