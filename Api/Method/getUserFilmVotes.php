<?php

namespace Orkan\Filmweb\Api\Method;

use Orkan\Filmweb\Logger;

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
	const USERID = 0;

	/**
	 * Response array keys
	 */
	const UPDATED  = 0;
	const FILMID   = 0;
	const DATE     = 1;
	const RATE     = 2;
	const FAV      = 3;
	const COMMENT  = 4;
	const FILMTYPE = 5; // 0 - movie, 1 - series

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		$format = $this . ' [%d, 1]';

		$str = sprintf( $format, $args[self::USERID] );

		Logger::debug( $str );
		Logger::info( $str );
		return $str;
	}
}
