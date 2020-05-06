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
	const ID     = 3;
	const GENDER = 4;
	const EXTRA1 = 5;
	const EXTRA2 = 6;
	const EXTRA3 = 7;

	public function format(array $args): string
	{
		$format = $this; // No args for this method. Only class name __toString()

		Logger::info($format);

		return $format;
	}
}
