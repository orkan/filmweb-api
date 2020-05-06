<?php

namespace Orkan\Filmweb\Api;

use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Transport\Transport;

class Api
{
	const URL = 'https://ssl.filmweb.pl/api';
	const VER = '1.0';
	const APP = 'android';
	const KEY = 'qjcGhW2JnvGT9dfCt3uT_jozR3s';

	private $send;
	private $status;
	private $output;

	public function __construct(Transport $t)
	{
		$this->send = $t;
	}

	public function call(string $method, array $args = []) : string
	{
		// Clear output buffers from previous method
		$this->status = $this->output = null;

		$method = __NAMESPACE__ . '\\Method\\' . $method; // cant use the 'use' statement
		$m = new $method;

		$response = $this->send->with(
			$m->type(),
			self::URL,
			self::query( $m->format($args) )
		);

		Logger::debug('Response: ' . $response);

		$r = explode("\n", $response);
		$this->status = isset($r[0]) ? $r[0] : 'null'; // ok|err
		$this->output = $r[1];

		Logger::info("status [{$this->status}]");

		if (in_array($this->status, ['err', 'null'])) {
			trigger_error($this->output, E_USER_ERROR);
		}

		return $this->status();
	}

	public function data(string $key = 'all') : array
	{
		$i = strrpos($this->output, ']');
		if (false === $i || '[' !== $this->output[0]) {
			trigger_error('No JSON data found', E_USER_ERROR);
		}

		$i += 1;
		$data1 = substr($this->output, 0, $i);
		$data2 = substr($this->output, $i);

		Logger::debug('json_decode: ' . $data1);
		Logger::debug('extra: ' . $data2);

		$json = json_decode($data1);
		if (null === $json) {
			trigger_error('Decoding JSON data failed', E_USER_ERROR);
		}

		$all = [
			'json'  => $json,
			'extra' => $data2,
		];

		if (array_key_exists($key, $all)) {
			return $all[$key];
		}

		return $all;
	}

	public function status() : string
	{
		return $this->status;
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

		Logger::debug(var_export($out, true));

		return http_build_query($out);
	}
}
