<?php

namespace Orkan\Filmweb\Api\Method;

use Orkan\Filmweb\Logger;

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
	const USERID = 0;

	/**
	 * Response array keys
	 */
	const UPDATED = 0;
	const FILMID  = 0;
	const ADDED   = 1;
	const LEVEL   = 2;

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