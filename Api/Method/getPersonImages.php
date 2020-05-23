<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getPersonImages extends Method
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
	const PERSON_IMAGE    = 0;
	const IMAGE_PERSONS   = 1; // array
	const   IMAGE_PERSON_ID    = 0;
	const   IMAGE_PERSON_NAME  = 1;
	const   IMAGE_PERSON_IMAGE = 2;
	const FILM_COPYRIGHTS = 2; // array
	const FILM_ID         = 3;
	const FILM_TITLE      = 4;
	const FILM_IMAGE      = 5;

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

		return sprintf( $this . ' [%u, %u, %u]', $args[self::ID], $cfg['offset'], $cfg['limit'] );
	}
}
