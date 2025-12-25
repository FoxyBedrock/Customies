<?php
declare(strict_types=1);

namespace customiesdevs\customies\test\state;

use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;

class IntState implements BlockState {

	private int $currentValue;

	public function __construct(
		private readonly string $name,
		private readonly array $values
	) {
		$this->currentValue = $values[0] ?? 0;
	}

	public function getName(): string {
		return $this->name;
	}

	public function getValue(): array {
		return [
			"enum" => $this->values,
			"name" => $this->name
		];
	}

	public function getCurrentValue(): int {
		return $this->currentValue;
	}

	public function setCurrentValue(mixed $value): void {
		$this->currentValue = (int) $value;
	}

	public function serialize(BlockStateWriter $writer): void {
		$writer->writeInt($this->name, $this->currentValue);
	}

	public function deserialize(BlockStateReader $reader): void {
		$this->currentValue = $reader->readInt($this->name);
	}
}