<?php

namespace Orkan\Filmweb\Api\Method;

final class login extends Method
{
	// Transport: [get|post]
	const TYPE = 'post';

	// Query arguments order
	const KEY = ['nickname' => 0, 'password' => 1];

	// Response array keys order
	const KEYS = [];

	public function prepare(array $args): string
	{
		return sprintf($this . ' ["%s", "%s", 1]', $args[self::KEY['nickname']], $args[self::KEY['password']]);
	}

	public function extract(array $data): array
	{
		return [$data];
		//$response = urldecode($response);
		//return [$this . ': ' . __function__ . "($response)"] + array_values(self::KEYS);
	}
}
