<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getFilmsNearestBroadcasts extends Method
{
	/**
	 * Send method
	 *
	 * @see Orkan\Filmweb\Transport: get(), post()
	 * @formatter:off
	 */
	const TYPE = 'get';

	/**
	 * Query array keys
	 */
	const IDS    = 0;
	const OFFSET = 1;
	const LIMIT  = 2;

	/**
	 * Response array keys
	 */
	const UNKNOW1 = 1;

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		if ( ! is_array( $args[self::IDS] ) ) {
			trigger_error( $this . ': Argument must be an array', E_USER_WARNING );
		}

		$offset = isset( $args[self::OFFSET] ) ? $args[self::OFFSET] : 0;
		$limit = isset( $args[self::LIMIT] ) ? $args[self::LIMIT] : 50;

		return sprintf( $this . ' [[628], %u, %u]', json_encode( $args[self::IDS] ), $offset, $limit );
	}
}
