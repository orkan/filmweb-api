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
	 * Debug mode ON/OFF
	 *
	 * @var bool
	 */
	private $is_debug;

	/**
	 * Dependency Injection Container
	 *
	 * @var Container
	 */
	private $app;

	public function __construct( Container $app )
	{
		$this->app = $app;

		// Configuration merged with defaults
		$this->app['cfg'] = array_merge( $this->getDefaults(), $this->app['cfg'] );

		$cfg = $this->app['cfg'];

		$this->logger = new \Monolog\Logger( $cfg['log_channel'] ); // %channel%
		$logformat = new \Monolog\Formatter\LineFormatter( $cfg['log_format'], $cfg['log_datetime'] );
		$logstream = new \Monolog\Handler\RotatingFileHandler( $cfg['log_file'], $cfg['log_keep'] );

		$logstream->setFormatter( $logformat );
		$this->logger->pushHandler( $logstream ); // DEBUG = 100; log everything, INFO = 200; log above >= 200
		$this->logger->setTimezone( new \DateTimeZone( $cfg['log_timezone'] ) );

		$this->is_debug = $cfg['is_debug'];
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
			'log_file'     => basename( __FILE__, 'php' ) . 'log',
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
	 * Get name of the last calling function (from outside of this class)
	 *
	 * @return string In format [Orkan\Filmweb\Filmweb->__construct()] $message
	 */
	private function backtrace()
	{
		$level = 2; // backtrace history steps back

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
		if ( ! $this->is_debug ) {
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
}
