<?php

namespace Orkan\Filmweb;

use Pimple\Container;

/**
 * A logging class
 *
 * @author Orkan
 */
class Logger
{
	/**
	 * Logger instance
	 *
	 * @var \Monolog\Logger
	 */
	private $logger;

	/**
	 * Dependency Injection Container
	 *
	 * @var Container
	 */
	private $app;

	public function __construct( Container $app )
	{
		$this->app = $app;

		// Merge configuration with defaults
		$this->app['cfg'] = array_merge( $this->getDefaults(), $this->app['cfg'] );

		$cfg = $this->app['cfg'];

		// Create the Logger
		$this->logger = new \Monolog\Logger( $cfg['log_channel'] ); // %channel%
		$logformat = new \Monolog\Formatter\LineFormatter( $cfg['log_format'], $cfg['log_datetime'] );
		$logstream = new \Monolog\Handler\RotatingFileHandler( $cfg['log_file'], $cfg['log_keep'] );
		$logstream->setFormatter( $logformat );
		$this->logger->pushHandler( $logstream ); // DEBUG = 100; log everything, INFO = 200; log above >= 200
		$this->logger->setTimezone( new \DateTimeZone( $cfg['log_timezone'] ) );

		// Remove sensitive data from log
		if ( isset( $this->app['cfg']['logger_mask'] ) ) {
			$search = $this->app['cfg']['logger_mask']['search'];
			$replace = $this->app['cfg']['logger_mask']['replace'];
			$this->logger->pushProcessor( function ( $entry ) use ($search, $replace ) {
				$entry['message'] = str_replace( $search, $replace, $entry['message'] );
				return $entry;
			} );
		}
	}

	/**
	 * Get default config
	 *
	 * @return array Default config
	 */
	public function getDefaults()
	{
		/* @formatter:off */
		return array(
			'log_channel'  => basename( __FILE__ ),
			'log_file'     => dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'filmweb-api.log',
			'log_timezone' => 'UTC', // @see https://www.php.net/manual/en/timezones.php
			'is_debug'     => false,

			/* Leave these for \Monolog defaults or define your own in $cfg */
			'log_keep'     => 0,    // \Monolog\Handler\RotatingFileHandler->maxFiles
			'log_datetime' => null, // 'Y-m-d\TH:i:s.uP'
			'log_format'   => null, // "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
		);
		/* @formatter:on */
	}

	/**
	 * Get the name of last calling function
	 *
	 * @return string In format [Namespace\Class->method()] $message
	 */
	private function backtrace()
	{
		$level = 2; // backtrace history (before this class)

		// https://www.php.net/manual/en/function.debug-backtrace.php
		$trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, $level + 1 );

		return isset( $trace[$level] ) ? "[{$trace[$level]['class']}{$trace[$level]['type']}{$trace[$level]['function']}()] " : '';
	}

	/**
	 * Call \Monolog\Logger instance only if in debug mode
	 *
	 * @param string $message
	 */
	public function debug( string $message ): void
	{
		if ( ! $this->app['cfg']['is_debug'] ) {
			return;
		}

		$this->logger->debug( $this->backtrace() . $message );
	}

	/**
	 * Call \Monolog\Logger instance
	 *
	 * @param string $message
	 */
	public function error( string $message ): void
	{
		$this->logger->error( $this->backtrace() . $message );
	}

	/**
	 * Call \Monolog\Logger instance
	 *
	 * @param string $message
	 */
	public function warning( string $message ): void
	{
		$this->logger->warning( $message );
	}

	/**
	 * Call \Monolog\Logger instance
	 *
	 * @param string $message
	 */
	public function notice( string $message ): void
	{
		$this->logger->notice( $message );
	}

	/**
	 * Call \Monolog\Logger instance
	 *
	 * @param string $message
	 */
	public function info( string $message ): void
	{
		$this->logger->info( $message );
	}

	/**
	 * Dumb method to cover error type in Filmweb->errorHandler()
	 *
	 * @param string $message
	 */
	public function unknown( string $message ): void
	{
		$this->logger->info( $message );
	}
}
