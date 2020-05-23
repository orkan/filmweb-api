<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getUserFilmsWantToSee extends Method
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
	const ID = 0;

	/**
	 * Response array keys
	 */
	const FILM_ID    = 0;
	const FILM_ADDED = 1;
	const FILM_LEVEL = 2;
	const UNKNOWN3   = 3;

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		return sprintf( $this . ' [%u, 1]', $args[self::ID] );
	}
}
