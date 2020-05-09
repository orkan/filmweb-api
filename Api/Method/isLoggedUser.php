<?php

namespace Orkan\Filmweb\Api\Method;

use Orkan\Filmweb\Logger;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class isLoggedUser extends Method
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
	const NICK   = 0;
	const AVATAR = 1;
	const NAME   = 2;
	const USERID = 3;
	const GENDER = 4;

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		$format = (string) $this; // No args for this method. Only class name __toString()

		Logger::debug( $format );
		Logger::info( $format );
		return $format;
	}
}
