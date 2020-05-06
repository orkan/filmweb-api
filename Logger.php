<?php

namespace Orkan\Filmweb;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

/**
 * A semi-singleton logging class
 * Usage:
 * Logger::init('file.log'); // Initialization required first!
 * Logger::debug('some message'); // later in code...
 * @author Orkan
 *
 */
class Logger
{
	private static $instance;

	/**
	 * Initialize logger with this file
	 */
	public static function init($file)
	{
		if (! self::$instance) {
			// https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md
			$logger    = new \Monolog\Logger('filmweb'); // %channel%
			$logline   = new LineFormatter('[%datetime%] %level_name%: %message%' . PHP_EOL, 'Y-m-d H:i:s'); // %context%
			$logstream = new StreamHandler($file);
			$logstream->setFormatter($logline);
			$logger->pushHandler($logstream); // DEBUG = 100; - log everything, INFO  = 200; - log above >= 200

			self::$instance = $logger;
		}
	}

	// Example: [Orkan\Filmweb\Filmweb->__construct()] $message
	private static function caller()
	{
		$level = 2; // caller history step back

		// https://www.php.net/manual/en/function.debug-backtrace.php
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $level + 1);

		return isset($trace[$level]) ? "[{$trace[$level]['class']}{$trace[$level]['type']}{$trace[$level]['function']}()] " : '';
	}

	public static function debug($message)
	{
		if (! DEBUG) {
			return;
		}

		self::$instance->debug(self::caller() . $message);
	}

	public static function error($message)
	{
		self::$instance->error(self::caller() . $message);
	}

	public static function warning($message)
	{
		self::$instance->warning($message);
	}

	public static function notice($message)
	{
		self::$instance->notice($message);
	}

	public static function info($message)
	{
		self::$instance->info($message);
	}
}