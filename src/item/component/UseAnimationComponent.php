<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class UseAnimationComponent implements ItemComponent {

	public const ANIMATION_NONE = 0;
	public const ANIMATION_EAT = 1;
	public const ANIMATION_DRINK = 2;
	public const ANIMATION_BLOCK = 3;
	public const ANIMATION_BOW = 4;
	public const ANIMATION_CAMERA = 5;
	public const ANIMATION_SPEAR = 6;
	public const ANIMATION_CROSSBOW = 9;
	public const ANIMATION_SPYGLASS = 10;
	public const ANIMATION_BRUSH = 12;

	private int $animation;

	/**
	 * Determines which animation plays when using an item.
	 * @param int $animation Specifies which animation to play when the the item is used.
	 */
	public function __construct(int $animation) {
		$this->animation = $animation;
	}

	public function getName(): string {
		return 'minecraft:use_animation';
	}

	public function getValue(): array {
		return [
			"value" => $this->animation
		];
	}

	public function getPropertyMapping(): ?array {
		return ['use_animation' => 'value'];
	}

	public static function fromJson(mixed $data): static {
		return new self($data ?? self::ANIMATION_NONE);
	}
}