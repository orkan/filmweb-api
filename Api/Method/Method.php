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
	 * Format Filmweb API method string
	 *
	 * @param array $args
	 * @return string
	 */
	abstract protected function format( array $args ): string;

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
