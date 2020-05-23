<?php

namespace Orkan\Filmweb\Api\Method;

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
	const USER_NICK   = 0;
	const USER_AVATAR = 1;
	const USER_NAME   = 2;
	const USER_ID     = 3;
	const USER_GENDER = 4;

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		return $this; // No args for this method. Only class name __toString()
	}
}
