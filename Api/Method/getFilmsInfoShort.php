<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getFilmsInfoShort extends Method
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
	const IDS = 0; // Array of film id's

	/**
	 * Response array keys
	 */
	const FILM_TITLE    = 0;
	const FILM_YEAR     = 1;
	const FILM_RATE     = 2;
	const FILM_VOTES    = 3;
	const FILM_DURATION = 4;
	const FILM_IMAGE    = 5;
	const FILM_ID       = 6;

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

		return sprintf( $this . ' [%s]', json_encode( $args[self::IDS] ) );
	}
}
