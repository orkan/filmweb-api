<?php

namespace Orkan\Filmweb;

use Orkan\Filmweb\Api\Api;
use Orkan\Filmweb\Api\Method\login;
use Orkan\Filmweb\Transport\Curl;

class Filmweb
{
	private $api;
	private $cfg;

	public function __construct($login, $pass, $options = [])
	{
		$this->cfg = $options;
		set_error_handler(array($this, 'errorHandler'));

		$transport = new Curl(['cookie' => $this->cfg['cookie']]);
		$this->api = new Api($transport);
		$this->api->call('login', [
			login::KEY['nickname'] => $login,
			login::KEY['password'] => $pass,
		]);
	}

	public function getApi()
	{
		return $this->api;
	}

	public function errorHandler($errno, $errstr, $errfile, $errline)
	{
		if (!(error_reporting() & $errno)) {
			// This error code is not included in error_reporting, so let it fall
			// through to the standard PHP error handler
			return false;
		}

		$is_filmweb = (E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE) & $errno;
		$msg = $is_filmweb ? 'Filmweb' : 'PHP';

		switch ($errno) {
			case E_ERROR:
			case E_USER_ERROR:
			$msg .= " Error";
			break;

			case E_WARNING:
			case E_USER_WARNING:
			$msg .= " Warning";
			break;

			case E_NOTICE:
			case E_USER_NOTICE:
			$msg .= " Notice";
			break;

			default:
			$msg .= " Unknown error [$errno]";
		}

		$msg = "$msg: $errstr in $errfile on line $errline\n";

		// Redirect output to STDERR if in CLI mode
		if (php_sapi_name() == "cli") {
			fwrite(STDERR, iconv('utf-8', $this->cfg['cli_codepage'], $msg));
		} else {
			echo $msg;
		}

		exit(1); // A response code other than 0 is a failure

		/* Don't execute PHP internal error handler */
		//return true;
	}

}
