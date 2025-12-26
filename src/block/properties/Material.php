<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\properties;

use pocketmine\nbt\tag\ByteTag;

final class Material {

	public const TARGET_ALL = "*";
	public const TARGET_SIDES = "sides";
	public const TARGET_UP = "up";
	public const TARGET_DOWN = "down";
	public const TARGET_NORTH = "north";
	public const TARGET_EAST = "east";
	public const TARGET_SOUTH = "south";
	public const TARGET_WEST = "west";

	public const FACE_DIMMING = 1;
	public const RANDOMIZE_UV_ROTATION = 2;
	public const SUPPORTS_TEXTURE_VARIATION = 4;

	private int $packed_bools;

	/**
	 * @param string $target
	 * Targeted face for the material.
	 * Valid values:
	 *  - "*"
	 *  - "sides"
	 *  - "up"
	 *  - "down"
	 *  - "north"
	 *  - "east"
	 *  - "south"
	 *  - "west"
	 * @param string $texture
	 * Texture identifier used by this material instance.
	 * @param RenderMethod $renderMethod
	 * Render method controlling how the block is drawn
	 * (opaque, blend, alpha_test, etc).
	 * @param TintMethod $tintMethod
	 * Tint logic applied to the texture (biome-based, none, etc).
	 * @param float $ambientOcclusion
	 * Ambient occlusion strength. Typically `1.0`.
	 * @param bool $face_dimming
	 * Whether face dimming/shading is enabled for the block.
	 * @param bool $isotropic
	 * Whether randomized UV rotation is enabled for the block.
	 */
	public function __construct(
		private readonly string $target,
		private readonly string $texture,
		private readonly RenderMethod $renderMethod = RenderMethod::OPAQUE,
		private readonly TintMethod $tintMethod = TintMethod::NONE,
		private readonly float $ambientOcclusion = 1.0,
		private readonly bool $face_dimming = true,
		private readonly bool $isotropic = false,
	) {
		$this->packed_bools = self::SUPPORTS_TEXTURE_VARIATION
			| ($face_dimming ? self::FACE_DIMMING : 0)
			| ($isotropic ? self::RANDOMIZE_UV_ROTATION : 0);
	}

	/**
	 * Returns the targeted face for the material.
	 * @return string The targeted face for the material.
	 */
	public function getTarget(): string {
		return $this->target;
	}

	/**
	 * Creates a Material instance from a decoded material definition.
	 * @param string $target
	 * @param array{
	 *   texture: string,
	 *   render_method?: string,
	 *   tint_method?: string,
	 *   ambient_occlusion?: float|int,
	 * } $data
	 */
	public static function fromArray(string $target, array $data): self {
		return new self(
			$target,
			$data["texture"],
			RenderMethod::tryFrom($data["render_method"] ?? "") ?? RenderMethod::OPAQUE,
			TintMethod::tryFrom($data["tint_method"] ?? "") ?? TintMethod::NONE,
			(float) ($data["ambient_occlusion"] ?? 1.0),
			$data["face_dimming"] ?? true,
			$data["isotropic"] ?? false
		);
	}

	/**
	 * Converts the material into Bedrock-compatible NBT format.
	 * @return array{
	 *   ambient_occlusion: float,
	 *   packed_bools: ByteTag,
	 *   render_method: string,
	 *   texture: string,
	 *   tint_method: string,
	 * }
	 */
	public function toArray(): array {
		return [
			"ambient_occlusion" => $this->ambientOcclusion,
			"packed_bools" => new ByteTag($this->packed_bools),
			"render_method" => $this->renderMethod->value,
			"texture" => $this->texture,
			"tint_method" => $this->tintMethod->value,
		];
	}
}