<?php

namespace Orkan\Filmweb\Api\Method;

/**
 * Get Filmweb API method string
 *
 * @author Orkan
 */
final class getPersonInfoFull extends Method
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
	const PERSON_NAME           = 0;
	const PERSON_BIRTHDAY       = 1;
	const PERSON_BIRTHPLACE     = 2;
	const PERSON_UNKNOWN3       = 3;
	const PERSON_COMMUNITY_RATE = 4;
	const PERSON_IMAGE          = 5;
	const PERSON_UNKNOWN6       = 6;
	const PERSON_UNKNOWN7       = 7;
	const PERSON_UNKNOWN8       = 8;
	const PERSON_FULL_NAME      = 9;
	const PERSON_UNKNOWN10      = 10;
	const PERSON_HEIGHT         = 11;
	const PERSON_NOTES_COUNT    = 12;

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		return sprintf( $this . ' [%u]', $args[self::ID] );
	}
}
