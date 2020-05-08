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

	private $calls = 0; // Current call
	private $limit_call;
	private $limit_usec;

	private $send;
	private $request;
	private $response;
	private $status;
	private $output;

	public function __construct(Transport $t, array $cfg)
	{
		$this->send = $t;
		$this->limit_call = $cfg['limit_call'];
		$this->limit_usec = $cfg['limit_usec'];
	}

	public function call(string $method, array $args = []) : string
	{
		// Slow down API calls overload
		$this->slowdown();

		// Clear output buffers from previous method
		$this->status = $this->output = $this->request = $this->response = null;

		$method = __NAMESPACE__ . '\\Method\\' . $method; // cant use the 'use' statement
		$m = new $method;

		$this->request  = $m->format($args);

		$this->response = $this->send->with(
			$m->getType(),
			self::URL,
			self::getQuery( $this->request )
		);

		Logger::debug('Response: ' . $this->response);

		$r = explode("\n", $this->response);
		$this->status = isset($r[0]) ? $r[0] : 'null'; // ok|err
		$this->output = $r[1];

		Logger::info("status [{$this->status}]");

		if (in_array($this->status, ['err', 'null'])) {
			trigger_error($this->output, E_USER_ERROR);
		}

		return $this->getStatus();
	}

	public function getData(string $key = 'all') : array
	{
		$i = strrpos($this->output, ']');
		if (false === $i || '[' !== $this->output[0]) {
			trigger_error('No JSON getData found', E_USER_ERROR);
		}

		$i += 1;
		$data1 = substr($this->output, 0, $i);
		$data2 = substr($this->output, $i);

		Logger::debug('json_decode: ' . $data1);
		Logger::debug('extra: ' . $data2);

		$json = json_decode($data1);
		if (null === $json) {
			trigger_error('Decoding JSON getData failed', E_USER_ERROR);
		}

		$all = [
			'json'  => $json,
			'extra' => $data2,
			'raw'   => $this->output,
		];

		if (array_key_exists($key, $all)) {
			return $all[$key];
		}

		return $all;
	}

	private static function getQuery(string $method) : string
	{
		$met = $method . '\n'; // required ?!
		$out = [
			'methods'   => $met,
			'signature' => self::VER . ',' . md5($met . self::APP . self::KEY),
			'version'   => self::VER,
			'appId'     => self::APP,
		];

		Logger::debug(Logger::print_r($out));

		return http_build_query($out);
	}

	public function getStatus() : string
	{
		return $this->status;
	}

	public function getRequest() : string
	{
		return $this->request;
	}

	public function getResponse() : string
	{
		return $this->response;
	}

	private function slowdown() : void
	{
		if (0 == ++$this->calls % $this->limit_call) {
			Logger::debug("[" . $this->calls . "] Slipping for " . $this->limit_usec . " microseconds...");
			usleep($this->limit_usec);
		}
	}
}
