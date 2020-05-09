<?php

namespace Orkan\Filmweb;

/**
 * A semi-singleton logging class
 * Usage:
 * Logger::init($cfg); // Initialize logger first!
 * Logger::debug('some message'); // later in code...
 *
 * @author Orkan
 *
 */
class Logger
{
	private static $instance;

	/**
	 * Initialize logger with this file
	 */
	public static function init( $cfg )
	{
		if ( ! self::$instance ) {
			// https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md
			$logger = new \Monolog\Logger( $cfg[ 'log_channel' ] ); // %channel%
			$logformat = new \Monolog\Formatter\LineFormatter( $cfg[ 'log_format' ], defined( 'FILMWEB_DEBUG' ) ? null : $cfg[ 'log_datetime' ] );
			$logstream = new \Monolog\Handler\RotatingFileHandler( $cfg[ 'log_file' ], $cfg[ 'log_keep' ] );

			$logstream->setFormatter( $logformat );
			$logger->pushHandler( $logstream ); // DEBUG = 100; log everything, INFO = 200; log above >= 200
			$logger->setTimezone( new \DateTimeZone( $cfg[ 'log_timezone' ] ) );

			self::$instance = $logger;
		}
	}

	/**
	 * Get name of the last calling function (from outside of this class)
	 *
	 * @return string I.e. [Orkan\Filmweb\Filmweb->__construct()] $message
	 */
	private static function caller()
	{
		$level = 2; // caller history steps back

		// https://www.php.net/manual/en/function.debug-backtrace.php
		$trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, $level + 1 );

		return isset( $trace[ $level ] ) ? "[{$trace[$level]['class']}{$trace[$level]['type']}{$trace[$level]['function']}()] " : '';
	}

	/**
	 * Call \Monolog\Logger instance only if in debug mode
	 *
	 * @param string $message
	 */
	public static function debug( string $message ): void
	{
		if ( ! defined( 'FILMWEB_DEBUG' ) ) {
			return;
		}

		self::$instance->debug( self::caller() . $message );
	}

	/**
	 * Call \Monolog\Logger instance
	 *
	 * @param string $message
	 */
	public static function error( string $message ): void
	{
		self::$instance->error( self::caller() . $message );
	}

	/**
	 * Call \Monolog\Logger instance
	 *
	 * @param string $message
	 */
	public static function warning( string $message ): void
	{
		self::$instance->warning( $message );
	}

	/**
	 * Call \Monolog\Logger instance
	 *
	 * @param string $message
	 */
	public static function notice( string $message ): void
	{
		self::$instance->notice( $message );
	}

	/**
	 * Call \Monolog\Logger instance
	 *
	 * @param string $message
	 */
	public static function info( string $message ): void
	{
		self::$instance->info( $message );
	}
}
