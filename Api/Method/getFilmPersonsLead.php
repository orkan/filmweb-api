<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getFilmPersonsLead extends Method
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
	const PERSON_ROLE    = 0; // @see Orkan\Filmweb\Api\Method\PersonRole
	const PERSON_ID      = 1;
	const CHARACTER_NAME = 2;
	const EXTRA          = 3; // additional info
	const PERSON_NAME    = 4;
	const PERSON_IMAGE   = 5;

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

		return sprintf( $this . ' [%u, %u]', $args[self::ID], $cfg['limit'] );
	}
}
