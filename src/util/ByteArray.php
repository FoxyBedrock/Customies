<?php
declare(strict_types=1);

namespace customiesdevs\customies\util;

/**
 * Wrapper class to indicate an array should be converted to a ListTag of ByteTags.
 * 
 * Usage:
 * ```php
 * "enum" => new ByteArray([false, true])  // Outputs: [B; 0b, 1b]
 * ```
 */
final class ByteArray {

	/** @var int[] */
	private readonly array $values;

	/**
	 * @param array<bool|int> $values Array of booleans or integers (0-255)
	 */
	public function __construct(array $values) {
		$this->values = array_map(
			fn(bool|int $v) => is_bool($v) ? ($v ? 1 : 0) : $v,
			$values
		);
	}

	/**
	 * @return int[]
	 */
	public function getValues(): array {
		return $this->values;
	}
}
