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

	/**
	 * @param string $target The targeted face for the material. Possible values are: "*", "sides", "up", "down", "north", "east", "south", "west".
	 * @param string $texture Texture name for the material.
	 * @param RenderMethod $renderMethod The render method to use.
	 * @param TintMethod $tintMethod Tint multiplied to the color. Tint method logic varies, but often refers to the "rain" and "temperature" of the biome the block is placed in to compute the tint.
	 * @param float $ambientOcclusion If this material has ambient occlusion applied when lighting, shadows will be created around and underneath the block. Decimal value controls exponent applied to a value after lighting.
	 * @param boolean $faceDimming This material should be dimmed by the direction it's facing.
	 * @param boolean $isotropic Should the faces that this material is applied to randomize their UVs?
	 */
	public function __construct(
		private readonly string $target,
		private readonly string $texture,
		private readonly RenderMethod $renderMethod = RenderMethod::OPAQUE,
		private readonly TintMethod $tintMethod = TintMethod::NONE,
		private readonly float $ambientOcclusion = 1.0,
		private readonly bool $faceDimming = false,
		private readonly bool $isotropic = false
	) {}

	/**
	 * Returns the targeted face for the material.
	 * @return string The targeted face for the material.
	 */
	public function getTarget(): string {
		return $this->target;
	}

	public static function fromArray(string $target, array $data): self {
		return new self(
			$target,
			$data["texture"],
			RenderMethod::tryFrom($data["render_method"] ?? "") ?? RenderMethod::OPAQUE,
			TintMethod::tryFrom($data["tint_method"] ?? "") ?? TintMethod::NONE,
			(float)($data["ambient_occlusion"] ?? 1.0),
			(bool)($data["packed_bools"] ?? 0x01),
			(bool)($data["isotropic"] ?? false)
		);
	}	
	
	public function toArray(): array {
		return [
			"texture" => $this->texture,
			"render_method" => $this->renderMethod->value,
			"tint_method" => $this->tintMethod->value,
			"ambient_occlusion" => $this->ambientOcclusion,	
			"packed_bools" => $this->faceDimming,
			"isotropic" => $this->isotropic
		];
	}
}
