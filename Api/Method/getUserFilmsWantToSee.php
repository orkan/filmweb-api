<?php

namespace Orkan\Filmweb\Api\Method;

use Orkan\Filmweb\Logger;

final class getUserFilmsWantToSee extends Method
{
	// Transport: [get|post]
	const TYPE = 'get';

	// Query array keys:
	const USERID = 0;

	// Response array keys:
	const UPDATED = 0;
	const FILMID  = 0;
	const ADDED   = 1;
	const LEVEL   = 2;

	public function format(array $args): string
	{
		$format = $this . ' [%d, 1]'; // Args: userid

		$str = sprintf($format, $args[self::USERID]);

		Logger::debug($str);
		Logger::info($str);
		return $str;
	}
}
