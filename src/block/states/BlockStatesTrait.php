<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\states;

trait BlockStatesTrait {

	private array $states = [];

	public function addState(BlockState $state): void {
		$this->states[$state->getName()] = $state;
	}

	public function hasState(string $name): bool {
		return isset($this->states[$name]);
	}

	public function getState(string $name): ?BlockState {
		return $this->states[$name] ?? null;
	}

	public function getStates(): array {
		return $this->states;
	}
}
