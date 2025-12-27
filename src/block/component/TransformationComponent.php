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
			"RXP" => (float) $this->rotationPivot->x,
			"RYP" => (float) $this->rotationPivot->y,
			"RZP" => (float) $this->rotationPivot->z,
			"SX" => (float) $this->scale->x,
			"SY" => (float) $this->scale->y,
			"SZ" => (float) $this->scale->z,
			"SXP" => (float) $this->scalePivot->x,
			"SYP" => (float) $this->scalePivot->y,
			"SZP" => (float) $this->scalePivot->z,
			"TX" => (float) $this->translation->x,
			"TY" => (float) $this->translation->y,
			"TZ" => (float) $this->translation->z,
			"hasJsonVersionBeforeValidation" => false
		];
	}
}