<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getPersonFilmsLead extends Method
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
	const FILM_ID        = 0;
	const CHARACTER_NAME = 1;
	const FILM_TITLE_PL  = 2;
	const FILM_IMAGE     = 3;
	const FILM_YEAR      = 4;
	const FILM_EXTRA     = 5;
	const FILM_TITLE     = 6;

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		$cfg = $this->getDefaults( $args );

		return sprintf( "$this [%u, %u]", $args[self::ID], $cfg['limit'] );
	}
}
