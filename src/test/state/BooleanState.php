<?php
declare(strict_types=1);

namespace customiesdevs\customies\test\state;

use customiesdevs\customies\util\ByteArray;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;

class BooleanState implements BlockState {

	private bool $currentValue = false;

	public function __construct(private readonly string $name) {}

	public function getName(): string {
		return $this->name;
	}

	public function getValue(): array {
		return [
			"enum" => new ByteArray([false, true]),
			"name" => $this->name
		];
	}

	public function getCurrentValue(): bool {
		return $this->currentValue;
	}

	public function setCurrentValue(mixed $value): void {
		$this->currentValue = (bool) $value;
	}

	public function serialize(BlockStateWriter $writer): void {
		$writer->writeBool($this->name, $this->currentValue);
	}

	public function deserialize(BlockStateReader $reader): void {
		$this->currentValue = $reader->readBool($this->name);
	}
}