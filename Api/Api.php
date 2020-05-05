<?php

namespace Orkan\Filmweb\Api;

use Orkan\Filmweb\Transport\Transport;

class Api
{
	const URL = 'https://ssl.filmweb.pl/api';
	const VER = '1.0';
	const APP = 'android';
	const KEY = 'qjcGhW2JnvGT9dfCt3uT_jozR3s';
	private $send;

	public function __construct(Transport $t)
	{
		$this->send = $t;
	}

	public function call(string $method, array $args = []) : array
	{
		$method = __NAMESPACE__ . '\\Method\\' . $method; // cant use the 'use' statement
		$m = new $method;
		$response = $this->send->with(
			$m->type(),
			self::URL,
			self::query($m->prepare($args))
		);

		return $m->extract($this->validate($response));
	}

	private function validate(string $response) : array
	{
		$r = explode("\n", $response);
		$status = $r[0];
		$output = $r[1];

		if('err' == $status) {
			trigger_error($output, E_USER_ERROR);
		}

		$i = strrpos($output, ']');
		if (false === $i || '[' !== $output[0]) {
			trigger_error('No JSON data found', E_USER_ERROR);
		}

		$data1 = substr($output, 0, $i);
		$data2 = substr($output, $i);

		$json = json_decode($data1);
		if (null === $json) {
			trigger_error('Decoding JSON data failed', E_USER_ERROR);
		}

		return ['json' => $json, 'extra' => $data2];
	}

	private static function query(string $method) : string
	{
		$met = $method . '\n'; // required ?!
		$out = [
			'methods'   => $met,
			'signature' => self::VER . ',' . md5($met . self::APP . self::KEY),
			'version'   => self::VER,
			'appId'     => self::APP,
		];
		return http_build_query($out);
	}
}
