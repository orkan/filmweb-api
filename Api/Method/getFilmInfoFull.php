<?php

namespace Orkan\Filmweb\Api\Method;

use Orkan\Filmweb\Logger;

final class getFilmInfoFull extends Method
{
	// Transport: [get|post]
	const TYPE = 'get';

	// Query array keys:
	const FILMID = 0;

	// Response array keys:
	const TITLEPL   = 0;
	const TITLE     = 1;
	const RATE      = 2;
	const VOTES     = 3;
	const GENRES    = 4;
	const YEAR      = 5;
	const DURATION  = 6;
	const COMMENTS  = 7;
	const FORUM     = 8;
	const HASREVIEW = 9;
	const HASDESC   = 10;
	const IMAGE     = 11;
	const VIDEO     = 12;
	const DATE      = 13;
	const DATEPL    = 14;
	const FILMTYPE  = 15;
	const SEASONS   = 16;
	const EPISODES  = 17;
	const COUNTRY   = 18;
	const DESC      = 19;

	public function format(array $args): string
	{
		$format = $this . ' [%d]'; // Args: filmid

		$str = sprintf($format, $args[self::FILMID]);

		Logger::debug($str);
		Logger::info($str);

		return $str;
	}
}
