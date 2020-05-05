<?php

namespace Orkan\Filmweb\Transport;

abstract class Transport
{
	abstract protected function  get(string $url, string $query) : string;
	abstract protected function post(string $url, string $query) : string;

	public function with(string $send, string $url, string $query) : string
	{
		return $this->$send($url, $query);
	}
}
