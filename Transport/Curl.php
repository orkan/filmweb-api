<?php

namespace Orkan\Filmweb\Transport;

use Orkan\Filmweb\Logger;

final class Curl extends Transport
{
	private $defaults = [
		CURLOPT_USERAGENT      => self::USERAGENT,
		CURLOPT_CONNECTTIMEOUT => self::CONNECTTIMEOUT,
		CURLOPT_TIMEOUT        => self::TIMEOUT,
		CURLOPT_RETURNTRANSFER => self::RETURNTRANSFER,
		CURLOPT_SSL_VERIFYPEER => self::SSL_VERIFYPEER,
		CURLOPT_SSL_VERIFYHOST => self::SSL_VERIFYHOST,
	];

	public function __construct(array $args = [])
	{
		$this->defaults[CURLOPT_COOKIEJAR]  = $args['cookie'];
		$this->defaults[CURLOPT_COOKIEFILE] = $args['cookie'];

		Logger::debug(Logger::print_r($this->defaults));
	}

	public function get(string $url, string $query) : string
	{
		$options = [
			CURLOPT_URL => $url . '?' . $query,
		];

		Logger::debug(Logger::print_r($options));
		return $this->exec($options);
	}

	public function post(string $url, string $query) : string
	{
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => urldecode($query),
		];

		Logger::debug(Logger::print_r($options));
		return $this->exec($options);
	}

	private function exec(array $options) : string
	{
		$request = curl_init();
		curl_setopt_array($request, $options + $this->defaults);
		$response = curl_exec($request);
		curl_close($request);
		return $response;
	}
}
