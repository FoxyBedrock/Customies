<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DyeableComponent implements ItemComponent {

	/** @var int[] */
	private array $rgb;

	/**
	 * Allows the item to be dyed by cauldron water. Once dyed, the item will display the `dyed` texture defined in the `minecraft:icon` component rather than `default`.
	 * @param string $hex Hex color code (e.g. "#175882")
	 */
	public function __construct(string $hex) {
		$this->rgb = self::hexToRgb($hex);
	}

	public function getName(): string {
		return 'minecraft:dyeable';
	}

	public function getValue(): array {
		return [
			"default_color" => $this->rgb
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}

	private static function hexToRgb(string $hex): array {
		$hex = ltrim($hex, '#');
		if(strlen($hex) !== 6) throw new \InvalidArgumentException("Invalid hex color: {$hex}");
		return [
			hexdec(substr($hex, 0, 2)),
			hexdec(substr($hex, 2, 2)),
			hexdec(substr($hex, 4, 2))
		];
	}

	private static function rgbToHex(array $rgb): string {
		[$r, $g, $b] = $rgb;
		return sprintf("#%02x%02x%02x", $r, $g, $b);
	}

	public static function fromJson(mixed $data): static {
		if(isset($data["default_color"]) && is_array($data["default_color"])){
			return new self(self::rgbToHex($data["default_color"]));
		}
		return new self("#ffffff");
	}
}