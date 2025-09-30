<?php

namespace customiesdevs\customies\block\component;

use pocketmine\math\Vector3;

class CollisionBoxComponent implements BlockComponent {

	private bool $useCollisionBox;
	private Vector3 $origin;
	private Vector3 $size;

	/**
	 * Defines the area of the block that collides with entities. If set to true, default values are used. If set to false, the block's collision with entities is disabled. If this component is omitted, default values are used.
	 * @param Vector3 $origin Minimal position of the bounds of the collision box. "origin" is specified as [x, y, z] and must be in the range (-8, 0, -8) to (8, 16, 8), inclusive.
	 * @param Vector3 $size Size of each side of the collision box. Size is specified as [x, y, z]. "origin" + "size" must be in the range (-8, 0, -8) to (8, 16, 8), inclusive.
	 * @param bool $useCollisionBox If collision should be enabled, default is set to `true`.
	 */
	public function __construct(bool $useCollisionBox = true, Vector3 $origin = new Vector3(-8.0, 0.0, -8.0), Vector3 $size = new Vector3(16.0, 16.0, 16.0)) {
		$this->useCollisionBox = $useCollisionBox;
		$this->origin = $origin;
		$this->size = $size;
	}

	public function getName(): string {
		return "minecraft:collision_box";
	}

	public function getValue(): array {
		return [
			"enabled" => $this->useCollisionBox,
			"origin" => [
				$this->origin->getX(), 
				$this->origin->getY(), 
				$this->origin->getZ()
			],
			"size" => [
				$this->size->getX(), 
				$this->size->getY(), 
				$this->size->getZ()
			]
		];
	}
}