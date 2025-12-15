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
	public const FLAG_FACE_DIMMING       = 0x01;
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
	 * @param int $packedBools
	 * Bitmask controlling material behavior.
	 * Supported flags:
	 *  - {@see self::FLAG_FACE_DIMMING}
	 *  - {@see self::FLAG_RANDOM_UV_ROTATION}
	 *  - {@see self::FLAG_TEXTURE_VARIATION}
	 * @param bool $alphaMaskedTint
	 * Whether the tint should only apply to alpha-masked pixels.
	 */
	public function __construct(
		private readonly string $target,
		private readonly string $texture,
		private readonly RenderMethod $renderMethod = RenderMethod::OPAQUE,
		private readonly TintMethod $tintMethod = TintMethod::NONE,
		private readonly float $ambientOcclusion = 1.0,
		private readonly int $packedBools = self::FLAG_FACE_DIMMING,
		private readonly bool $alphaMaskedTint = false,
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
	 *   packed_bools?: int,
	 *   face_dimming?: bool|int,
	 *   isotropic?: bool|int,
	 *   alpha_masked_tint?: bool|int
	 * } $data
	 */
	public static function fromArray(string $target, array $data): self {
		$packedBools = 0;
		if(isset($data["packed_bools"])){
			$packedBools = (int) $data["packed_bools"];
		}else{
			if(!empty($data["face_dimming"])){
				$packedBools |= self::FLAG_FACE_DIMMING;
			}
			if(!empty($data["isotropic"])){
				$packedBools |= self::FLAG_RANDOM_UV_ROTATION;
			}
		}
		return new self(
			$target,
			$data["texture"],
			RenderMethod::tryFrom($data["render_method"] ?? "") ?? RenderMethod::OPAQUE,
			TintMethod::tryFrom($data["tint_method"] ?? "") ?? TintMethod::NONE,
			(float) ($data["ambient_occlusion"] ?? 1.0),
			$packedBools,
			(bool) ($data["alpha_masked_tint"] ?? false)
		);
	}

	/**
	 * Converts the material into Bedrock-compatible NBT/JSON format.
	 *
	 * @return array{
	 *   texture: string,
	 *   render_method: string,
	 *   tint_method: string,
	 *   ambient_occlusion: float,
	 *   packed_bools: int,
	 *   alpha_masked_tint: bool
	 * }
	 */
	public function toArray(): array {
		return [
			"texture" => $this->texture,
			"render_method" => $this->renderMethod->value,
			"tint_method" => $this->tintMethod->value,
			"ambient_occlusion" => $this->ambientOcclusion,
			"packed_bools" => $this->packedBools,
			"alpha_masked_tint" => $this->alphaMaskedTint
		];
	}
}