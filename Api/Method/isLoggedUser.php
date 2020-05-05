<?php

namespace Orkan\Filmweb\Api\Method;

final class isLoggedUser extends Method
{
	// Transport: [get|post]
	const TYPE = 'get';

	// Query arguments order
	// const KEY = [];

	// Response array keys order
	const KEYS = ['nick', 'avatar', 'name', 'id', 'gender'];

	public function prepare(array $args): string
	{
		return $this;
	}

	public function extract(array $data): array
	{
		return [$data];
		$data = urldecode($data);
		return [$this . ': ' . __function__ . "($data)"] + array_values(self::KEYS);
	}
}
