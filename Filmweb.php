<?php

namespace Orkan\Filmweb;

use Orkan\Filmweb\Api\Api;
use Orkan\Filmweb\Api\Method\login;
use Orkan\Filmweb\Transport\Curl;

class Filmweb
{
	const TITLE = 'Filmweb Api by Orkan';
	private $cfg;
	private $log;
	private $api;

	public function __construct(string $login, string $pass, array $cfg = [])
	{
		$this->cfg = $cfg;

		// These must be set!
		$this->setDefault('cli_codepage' , 'cp852');
		$this->setDefault('cookie_file'  , dirname(__FILE__) . DIRECTORY_SEPARATOR . "{$login}-cookie.txt");
		$this->setDefault('log_channel'  , self::TITLE);
		$this->setDefault('log_file'     , self::TITLE . '.log');
		$this->setDefault('log_timezone' , 'UTC'); // 'UTC' @see https://www.php.net/manual/en/timezones.php

		// Leave these for \Monolog to use setDefault or define your own in $cfg
		$this->setDefault('log_keep'    , 0);	   // RotatingFileHandler->maxFiles
		$this->setDefault('log_datetime', null); // 'Y-m-d\TH:i:s.uP'
		$this->setDefault('log_format'  , null); // "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"

		// Api call limiter
		$this->setDefault('limit_call', 8); // Calls between pauses
		$this->setDefault('limit_usec', 500); // Pause duration in microseconds
		
		set_error_handler([$this, 'errorHandler']); // Filmweb->errorHandler()

		Logger::init($this->cfg);
		Logger::info(self::getTitle()); // Introduce itself! :)

		$transport = new Curl(['cookie' => $this->cfg['cookie_file']]);
		$this->api = new Api($transport, $this->cfg);
		$this->api->call('login', [login::NICKNAME => $login, login::PASSWORD => $pass]);
	}

	private function setDefault(string $key, $value)
	{
		if (! isset($this->cfg[$key])) {
			$this->cfg[$key] = $value;
		}
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

		// If in CLI mode: redirect output to STDERR
		if ('cli' == php_sapi_name()) {
			fwrite(STDERR, iconv('utf-8', $this->cfg['cli_codepage'], $msg));
		}
		else {
			echo $msg;
		}

		Logger::$type($msg);

		if(in_array($type, ['error', 'warning'])) {
			exit(1); // A response code other than 0 is a failure
		}

		// Don't execute PHP internal error handler
		//return true;
	}

	public static function getTitle() : string
	{
		$u = str_repeat('_', 33);
		return $u . '[' . self::TITLE . ']' . $u;
	}

	// UTILS:
	// @todo: Make separate class tor it
	
	// The timestamp returned by filmweb is 3 digits too long?!? ... Cut it!
	// Example: 1588365133974 -> 1588365133
	public static function getTimestamp(string $timestamp) : string
	{
		$l = strlen(time());
		return strlen($timestamp) > $l ? substr($timestamp, 0, $l) : $timestamp;
	}
	
	public static function formatDate(string $timestamp, $format = DATE_RSS) : string
	{
		$t = self::getTimestamp($timestamp);
		return (new \DateTime(null, (new \DateTimeZone('Europe/Berlin'))))->setTimestamp($t)->format($format);
	}
	
}
