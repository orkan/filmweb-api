<?php

namespace Orkan\Filmweb\Api\Method;

class PersonRole
{
	/**
	 * Role types
	 * @formatter:off
	 */
	const EDITOR       =  1; // scenarzysta
	const DIRECTOR     =  2; // reżyser
	const CINEMA       =  3; // zdjęcia
	const MUSIC        =  4; // muzyka
	const DESIGNER     =  5; // scenografia
	const ACTOR        =  6; // aktor
	const PRODUCER     =  7; // producent
	const PRODUCTION   = 10; // montaż
	const COSTUMES     = 13; // kostiumy
	const SCREENWRITER = 17; // materiały do scenariusza
	const SOUND        = 18; // dźwięk
	const ARCHIVE      = 19; // materiały archiwalne
	const VOICE        = 20; // głos
	const SELF         = 21; // we własnej osobie
	/* @formatter:on */
}
