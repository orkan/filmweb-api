<?php

namespace Orkan\Filmweb\Api\Method;

use Orkan\Filmweb\Logger;

final class isLoggedUser extends Method
{
	// Transport: [get|post]
	const TYPE = 'get';

	// Query array keys:
	// const KEY = 0;

	// Response array keys:
	const NICK   = 0;
	const AVATAR = 1;
	const NAME   = 2;
	const USERID = 3;
	const GENDER = 4;

	public function format(array $args): string
	{
		$format = (string) $this; // No args for this method. Only class name __toString()

		Logger::debug($format);
		Logger::info($format);
		return $format;
	}
}
