<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getFilmPersons extends Method
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
	const ROLE   = 1; // @see: Orkan\Filmweb\Api\Method\PersonRole
	const OFFSET = 2;
	const LIMIT  = 3;

	/**
	 * Response array keys
	 */
	const PERSON_ID      = 0;
	const CHARACTER_NAME = 1;
	const PERSON_ATTR    = 2;
	const PERSON_NAME    = 3;
	const PERSON_IMAGE   = 4;

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

		return sprintf( $this . ' [%u, %u, %u, %u]', $args[self::ID], $args[self::ROLE], $cfg['offset'], $cfg['limit'] );
	}
}
