<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getFilmComments extends Method
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
	const ID     = 0;
	const OFFSET = 1;
	const LIMIT  = 2;

	/**
	 * Response array keys
	 */
	const COMMENTS = 0;

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		$offset = isset( $args[self::OFFSET] ) ? $args[self::OFFSET] : 0;
		$limit = isset( $args[self::LIMIT] ) ? $args[self::LIMIT] : 50;

		return sprintf( $this . ' [%u, %u, %u]', $args[self::ID], $offset, $limit );
	}
}
