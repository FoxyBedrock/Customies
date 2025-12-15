<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\properties;

final class Material {

	public const TARGET_ALL = "*";
	public const TARGET_SIDES = "sides";
	public const TARGET_UP = "up";
	public const TARGET_DOWN = "down";
	public const TARGET_NORTH = "north";
	public const TARGET_EAST = "east";
	public const TARGET_SOUTH = "south";
	public const TARGET_WEST = "west";

	/* packed_bools bit flags (byte) */
	/** Enables directional face shading */
	public const FLAG_FACE_DIMMING = 0x01;
	/** Enables randomized UV rotation per face */
	public const FLAG_RANDOM_UV_ROTATION = 0x02;
	/** Enables texture variation support */
	public const FLAG_TEXTURE_VARIATION  = 0x04;

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
	 */
	public function __construct(
		private readonly string $target,
		private readonly string $texture,
		private readonly RenderMethod $renderMethod = RenderMethod::OPAQUE,
		private readonly TintMethod $tintMethod = TintMethod::NONE,
		private readonly float $ambientOcclusion = 1.0
	) {}

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
			(float) ($data["ambient_occlusion"] ?? 1.0)
		);
	}

	/**
	 * Converts the material into Bedrock-compatible NBT/JSON format.
	 * @param int $packedBools Context-specific packed_bools value (1=item_visual, 4=permutations, 5=material_instances)
	 * @return array{
	 *   texture: string,
	 *   render_method: string,
	 *   tint_method: string,
	 *   ambient_occlusion: float,
	 * }
	 */
	public function toArray(int $packedBools = self::FLAG_TEXTURE_VARIATION): array {
		return [
			"texture" => $this->texture,
			"render_method" => $this->renderMethod->value,
			"tint_method" => $this->tintMethod->value,
			"ambient_occlusion" => $this->ambientOcclusion,
			"packed_bools" => $packedBools,
			// Fixed values
			"alpha_masked_tint" => false,
			"face_dimming" => true,
			"isotropic" => false,
		];
	}
}