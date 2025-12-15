<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\Box;
use pocketmine\math\Vector3;

class CollisionBoxComponent implements BlockComponent {

	private bool $enabled;
	/** @var Box[] */
	private array $boxes = [];

	/**
	 * Defines the area of the block that collides with entities.
	 * @param bool $enabled If collision should be enabled
	 */
	public function __construct(bool $enabled = true) {
		$this->enabled = $enabled;
	}

	/**
	 * Adds a single collision box.
	 * @param Box $box
	 * The collision box to add.
	 * @return $this
	 */
	public function addBox(Box $box): self {
		$this->boxes[] = $box;
		return $this;
	}

	/**
	 * Adds multiple collision boxes.
	 * @param Box[] $boxes
	 * An array of collision boxes to add.
	 * @return $this
	 */
	public function addBoxes(array $boxes): self {
		foreach($boxes as $box) {
			$this->boxes[] = $box;
		}
		return $this;
	}

	public function getName(): string {
		return 'minecraft:collision_box';
	}

	public function getValue(): array {
		$convertedBoxes = [];
		foreach($this->boxes as $box) {
			$convertedBoxes[] = $box->toNbtArray();
		}
		return [
			"enabled" => $this->enabled ? 1 : 0,
			"boxes" => $convertedBoxes
		];
	}

	public static function fromJson(mixed $data): static {
		// false or true
		if(is_bool($data)) {
			return new self($data);
		}
		
		$component = new self(true);
		$boxes = [];
		
		// Array of boxes
		if(is_array($data) && isset($data[0])) {
			foreach($data as $box) {
				$origin = $box['origin'] ?? [-8, 0, -8];
				$size = $box['size'] ?? [16, 24, 16];
				$boxes[] = new Box(
					new Vector3($origin[0], $origin[1], $origin[2]),
					new Vector3($size[0], $size[1], $size[2])
				);
			}
			return $component->addBoxes($boxes);
		}
		
		// Single box object
		if(is_array($data) && isset($data['origin'])) {
			$origin = $data['origin'];
			$size = $data['size'] ?? [16, 24, 16];
			return $component->addBox(new Box(
				new Vector3($origin[0], $origin[1], $origin[2]),
				new Vector3($size[0], $size[1], $size[2])
			));
		}
		
		return $component;
	}
}