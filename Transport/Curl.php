<?php

class Curl implements Transport {
	
	private $defaults = [
		CURLOPT_USERAGENT      => 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
		CURLOPT_CONNECTTIMEOUT => 5,
		CURLOPT_TIMEOUT        => 5,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
	];
	
	public function __construct($cookie = '') {
		$this->defaults[CURLOPT_COOKIEJAR]  = $cookie;
		$this->defaults[CURLOPT_COOKIEFILE] = $cookie;
	}
	
	public function get($url, $args = []) {
		$opt = [
			CURLOPT_URL => $url . http_build_query($args),
		];
		return exec($opt);
	}

	public function post($url, $args = []) {
		$opt = [
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => urldecode( http_build_query($args) ),
		];
		return exec($opt);
	}
	
	private function exec($opt) {
		$request = curl_init();
		curl_setopt_array($request, $opt + $this->defaults);
		$response = curl_exec($request);
		curl_close($request);
		return $response;
	}
}