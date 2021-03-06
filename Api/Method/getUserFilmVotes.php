<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getUserFilmVotes extends Method
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
	const FILM_ID      = 0;
	const FILM_DATE    = 1;
	const FILM_RATE    = 2;
	const FILM_FAV     = 3;
	const FILM_COMMENT = 4;
	const FILM_TYPE    = 5; // @see Orkan\Filmweb\Api\Method\FilmType

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
