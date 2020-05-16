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
	const FILMID = 0;
	const ROLE   = 1;

	/**
	 * Role types
	 */
	const ROLE_EDITOR       =  1; // scenarzysta
	const ROLE_DIRECTOR     =  2; // reżyser
	const ROLE_CINEMA       =  3; // zdjęcia
	const ROLE_MUSIC        =  4; // muzyka
	const ROLE_DESIGNER     =  5; // scenografia
	const ROLE_ACTOR        =  6; // aktor
	const ROLE_PRODUCER     =  7; // producent
	const ROLE_PRODUCTION   = 10; // montaż
	const ROLE_COSTUMES     = 13; // kostiumy
	const ROLE_SCREENWRITER = 17; // materiały do scenariusza
	const ROLE_SOUND        = 18; // dźwięk
	const ROLE_ARCHIVE      = 19; // materiały archiwalne
	const ROLE_VOICE        = 20; // głos
	const ROLE_SELF         = 21; // we własnej osobie

	/**
	 * Response array keys
	 */
	const PERSONID = 0; // personId
	const ANAME    = 1; // assocName
	const AATTR    = 2; // assocAttributes
	const NAME     = 3; // personName
	const PHOTO    = 4; // personImagePath

	/**
	 * Format method string
	 *
	 * @formatter:on
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Api\Method\Method::format()
	 */
	public function format( array $args ): string
	{
		return sprintf( $this . ' [%u, %u, 0, 50]', $args[ self::FILMID ], $args[ self::ROLE ] );
	}
}
