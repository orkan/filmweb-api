<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getPopularFilms extends Method
{
	/**
	 * Send method
	 *
	 * @see Orkan\Filmweb\Transport: get(), post()
	 * @formatter:off
	 */
	const TYPE = 'get';

	/**
	 * Response array keys
	 */
	const TITLE    = 0;
	const YEAR     = 1;
	const RATE     = 2;
	const VOTES    = 3;
	const DURATION = 4;
	const IMAGE    = 5;
	const FILM_ID  = 6;

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		return (string) $this;
	}
}
