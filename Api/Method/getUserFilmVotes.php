<?php

namespace Orkan\Filmweb\Api\Method;

use Orkan\Filmweb\Logger;

final class getUserFilmVotes extends Method
{
	// Transport: [get|post]
	const TYPE = 'get';

	// Query array keys:
	const USERID = 0;

	// Response array keys:
	const UPDATED  = 0;
	const FILMID   = 0;
	const DATE     = 1;
	const RATE     = 2;
	const FAV      = 3;
	const COMMENT  = 4;
	const FILMTYPE = 5; // 0 - movie, 1 - series

	public function format(array $args): string
	{
		$format = $this . ' [%d, 1]'; // Args: userid

		$str = sprintf($format, $args[self::USERID]);

		Logger::debug($str);
		Logger::info($str);
		return $str;
	}
}
