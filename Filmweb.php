<?php

namespace Orkan\Filmweb;

use Orkan\Filmweb\Api\Api;
use Orkan\Filmweb\Api\Method\login;
use Orkan\Filmweb\Transport\Curl;

class Filmweb
{
	const TITLE = '[Filmweb Api by Orkan]';
	private $cfg;
	private $log;
	private $api;

	public function __construct(string $login, string $pass, array $options = [])
	{
		set_error_handler(array($this, 'errorHandler'));
		$this->cfg = $options;

		$logfile = empty($this->cfg['log_file']) ? 'filmweb.log' : $this->cfg['log_file'];
		Logger::init($logfile);

		Logger::info(self::info()); // Introduce yourself :)

		$transport = new Curl(['cookie' => $this->cfg['cookie_file']]);
		$this->api = new Api($transport);
		$this->api->call('login', [login::NICKNAME => $login, login::PASSWORD => $pass]);
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
				$type = 'error';
			break;

			case E_WARNING:
			case E_USER_WARNING:
				$type = 'warning';
			break;

			case E_NOTICE:
			case E_USER_NOTICE:
				$type = 'notice';
			break;

			default:
				$type = 'unknown';
				$msg .= " [$errno]";
		}

		$msg = "$msg $type: $errstr in $errfile on line $errline\n";

		// Redirect output to STDERR if in CLI mode
		if (php_sapi_name() == "cli") {
			fwrite(STDERR, iconv('utf-8', $this->cfg['cli_codepage'], $msg));
		} else {
			echo $msg;
		}

		Logger::$type($msg);

		if(in_array($type, ['error', 'warning'])) {
			exit(1); // A response code other than 0 is a failure
		}

		//return true; // Don't execute PHP internal error handler
	}

	public static function info()
	{
		$u = str_repeat('_', 33);
		return $u . self::TITLE . $u;
	}
}
