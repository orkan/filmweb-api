<?php

namespace Orkan\Filmweb;

/**
 * A semi-singleton logging class
 * Usage:
 * Logger::init($cfg); // Initialize logger first!
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
	public static function init($cfg)
	{
		if (! self::$instance) {
			// https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md
			$logger    = new \Monolog\Logger($cfg['log_channel']); // %channel%
			$logformat = new \Monolog\Formatter\LineFormatter($cfg['log_format'], defined('FILMWEB_DEBUG') ? null : $cfg['log_datetime']);
			$logstream = new \Monolog\Handler\RotatingFileHandler($cfg['log_file'], $cfg['log_keep']);

			$logstream->setFormatter($logformat);
			$logger->pushHandler($logstream); // DEBUG = 100; log everything, INFO  = 200; log above >= 200
			$logger->setTimezone(new \DateTimeZone($cfg['log_timezone']));

			self::$instance = $logger;
		}
	}

	// Outputs: [Orkan\Filmweb\Filmweb->__construct()] $message
	private static function caller()
	{
		$level = 2; // caller history step back

		// https://www.php.net/manual/en/function.debug-backtrace.php
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $level + 1);

		return isset($trace[$level]) ? "[{$trace[$level]['class']}{$trace[$level]['type']}{$trace[$level]['function']}()] " : '';
	}

	public static function debug($message)
	{
		if (! defined('FILMWEB_DEBUG')) {
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

	public static function print_r(array $a)
	{
		$s = print_r($a, true);
		return preg_replace('/[ ]{2,}/', '', $s); // Remove double spaces
	}
}