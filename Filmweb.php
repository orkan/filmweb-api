<?php

class Filmweb {
	const $_cookie = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cookie-%s.txt';
	
	public function __construct($login, $pass) {
		$cookie    = sprintf(self::_cookie, $login);
		$transport = new Curl(['cookie' => $cookie]);
		
		$this->api = new Api($transport);
		$this->api->method('login', [$login, $pass]);
		$this->api->method('isLoggedUser');
		
		// przenies do: api/login.php
		//$this->api->method('isLoggedUser');
		// zapisz w Api userId
	}
	
	public function call($method, $params = []) {
		$api = new $method($params);
		
		$data = $api->get();
		return $data;
	}
	


}

