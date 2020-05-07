<?php

namespace Orkan\Filmweb\Api\Method;

use Orkan\Filmweb\Logger;

final class login extends Method
{
	// Transport: [get|post]
	const TYPE = 'post';

	// Query array keys:
	const NICKNAME = 0;
	const PASSWORD = 1;

	// Response array keys:
	//const KEY = 0;

	public function format(array $args): string
	{
		$format = $this . ' ["%s", "%s", 1]'; // Args: login, password
		
		$r = sprintf($format, $args[self::NICKNAME], $args[self::PASSWORD]);
		$l = sprintf($format, $args[self::NICKNAME], '---'); // Don't log passwords!

		Logger::debug($l);
		Logger::info($l);
		return $r;
	}
}
