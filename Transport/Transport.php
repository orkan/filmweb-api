<?php

namespace Orkan\Filmweb\Transport;

abstract class Transport
{
	protected const USERAGENT = 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3';
	protected const CONNECTTIMEOUT = 5;
	protected const TIMEOUT = 5;
	protected const RETURNTRANSFER = true;
	protected const SSL_VERIFYPEER = false;
	protected const SSL_VERIFYHOST = false;

	abstract protected function  get(string $url, string $query) : string;
	abstract protected function post(string $url, string $query) : string;

	public function with(string $send, string $url, string $query) : string
	{
		return $this->$send($url, $query);
	}
}
