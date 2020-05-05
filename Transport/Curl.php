<?php

namespace Orkan\Filmweb\Transport;

final class Curl extends Transport
{
	private $defaults = [
		CURLOPT_USERAGENT      => 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
		CURLOPT_CONNECTTIMEOUT => 5,
		CURLOPT_TIMEOUT        => 5,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
	];

	public function __construct(array $args = [])
	{
		$this->defaults[CURLOPT_COOKIEJAR]  = $args['cookie'];
		$this->defaults[CURLOPT_COOKIEFILE] = $args['cookie'];
//		echo "Curl: __construct({$args['cookie']})\n";
	}

	public function get(string $url, string $query) : string
	{
//		return "Curl: get($url$query)";
		$options = [
			CURLOPT_URL => $url . '?' . $query,
		];
		return $this->exec($options);
	}

	public function post(string $url, string $query) : string
	{
//		return "Curl: post($url$query)";
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => urldecode($query),
		];
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
