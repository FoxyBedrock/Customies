<?php

namespace customiesdevs\customies\block\component;

use pocketmine\math\Vector3;

class TransformationComponent implements BlockComponent {

	/**
	 * The block's translation, rotation and scale with respect to the center of its world position.
	 * @param Vector3 $rotation The block's rotation in increments of 90 degrees
	 * @param Vector3 $rotationPivot The point to apply rotation around	
	 * @param Vector3 $scale The block's scale
	 * @param Vector3 $scalePivot The point to apply scale around
	 * @param Vector3 $translation The block's translation
	 */
	public function __construct(
		private readonly Vector3 $rotation = new Vector3(0, 0, 0),
		private readonly Vector3 $rotationPivot = new Vector3(0, 0, 0),
		private readonly Vector3 $scale = new Vector3(1, 1, 1),
		private readonly Vector3 $scalePivot = new Vector3(0, 0, 0),
		private readonly Vector3 $translation = new Vector3(0, 0, 0)
	) {}

	public function getName(): string {
		return 'minecraft:transformation';
	}

	public function getValue(): array {
		$rx = match ((int) $this->rotation->x) {
			0 => 0,
			90 => 1,
			180 => 2,
			270, -90 => 3,
			default => 0
		};
		$ry = match ((int) $this->rotation->y) {
			0 => 0,
			90 => 1,
			180 => 2,
			270, -90 => 3,
			default => 0
		};
		$rz = match ((int) $this->rotation->z) {
			0 => 0,
			90 => 1,
			180 => 2,
			270, -90 => 3,
			default => 0
		};
		return [
			"RX" => $rx,
			"RY" => $ry,
			"RZ" => $rz,
			"RXP" => $this->rotationPivot->x,
			"RYP" => $this->rotationPivot->y,
			"RZP" => $this->rotationPivot->z,
			"SX" => $this->scale->x,
			"SY" => $this->scale->y,
			"SZ" => $this->scale->z,
			"SXP" => $this->scalePivot->x,
			"SYP" => $this->scalePivot->y,
			"SZP" => $this->scalePivot->z,
			"TX" => $this->translation->x,
			"TY" => $this->translation->y,
			"TZ" => $this->translation->z,
			"hasJsonVersionBeforeValidation" => false
		];
	}

	public static function fromJson(mixed $data): static {
		return new self(
			$data['rotation'] ?? new Vector3(0, 0, 0),
			$data['rotation_pivot'] ?? new Vector3(0, 0, 0),
			$data['scale'] ?? new Vector3(1, 1, 1),
			$data['scale_pivot'] ?? new Vector3(0, 0, 0),
			$data['translation'] ?? new Vector3(0, 0, 0)
		);
	}
}