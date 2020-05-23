<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Interface for Filmweb API methods
 *
 * @author Orkan
 */
abstract class Method
{
	/**
	 * Default response aggregation
	 */
	const DEFAULT_OFFSET = 0;
	const DEFAULT_LIMIT = 50;

	/**
	 * Format Filmweb API method string
	 *
	 * @param array $args
	 * @return string
	 */
	abstract protected function format( array $args ): string;

	/**
	 * Get defaults
	 *
	 * @return string
	 */
	public function getDefaults( array $args ): array
	{
		return array(
		/* @formatter:off */
			'offset' => isset( $args[static::OFFSET] ) ? $args[static::OFFSET] : self::DEFAULT_OFFSET,
			'limit'  => isset( $args[static::LIMIT]  ) ? $args[static::LIMIT]  : self::DEFAULT_LIMIT,
		);
		/* @formatter:on */
	}

	/**
	 * Get http send methd type post|get
	 *
	 * @return string
	 */
	public function getType(): string
	{
		return static::TYPE;
	}

	/**
	 * Convert this object to string
	 *
	 * @return string
	 */
	function __toString(): string
	{
		$parts = explode( '\\', get_class( $this ) );
		return array_pop( $parts );
	}
}
