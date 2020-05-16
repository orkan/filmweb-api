<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
class login extends Method
{
	/**
	 * Send method
	 *
	 * @see Orkan\Filmweb\Transport: get(), post()
	 * @formatter:off
	 */
	const TYPE = 'post';

	/**
	 * Query array keys
	 */
	const NICKNAME = 0;
	const PASSWORD = 1;

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		return sprintf( $this . ' ["%s", "%s", 1]', addslashes( $args[self::NICKNAME] ), addslashes( $args[self::PASSWORD] ) );
	}
}
