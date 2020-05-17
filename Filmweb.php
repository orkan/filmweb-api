<?php

namespace Orkan\Filmweb;

use Orkan\Filmweb\Api\Method\login;
use Pimple\Container;

/**
 * Non-official API for Filmweb.pl
 *
 * @author Orkan
 */
class Filmweb
{
	const TITLE = 'Filmweb Api by Orkan';

	/**
	 * Instance spawn time
	 *
	 * @var float microtime() at spawning time
	 */
	private $start_time = null;

	/**
	 * Dependency Injection Container
	 *
	 * @see https://pimple.symfony.com/
	 *
	 * @var Container
	 */
	private $app;

	/**
	 * Initialize child objects: Transport & Api
	 * Login to Filmweb
	 *
	 * @param string $login
	 * @param string $pass
	 * @param array $cfg Overrides for $this->app['cfg']
	 */
	public function __construct( string $login, string $pass, array $config = [] )
	{
		// Save start execution time
		$this->getExectime();

		// Create Dependency Injection Container
		$this->app = new Container();

		// Configuration merged with defaults
		$this->app['cfg'] = array_merge( $this->getDefaults(), $config );

		// Set Error Handler as soon as possible! (DI property)
		// @see $this->errorHandler()
		$this->app['errorHandler'] = array( $this, 'errorHandler' );
		set_error_handler( $this->app['errorHandler'] );

		// Create application services
		$this->app['logger'] = function ( $c ) {
			return new $this->app['cfg']['logger']( $c );
		};
		$this->app['send'] = function ( $c ) {
			return new $this->app['cfg']['tarnsport']( $c );
		};
		$this->app['api'] = function ( $c ) {
			return new $this->app['cfg']['api']( $c );
		};

		// Login to filmweb.pl
		$this->app['logger']->info( self::getTitle() ); // Introduce itself! :)
		$this->app['api']->call( 'login', array( login::NICKNAME => $login, login::PASSWORD => $pass ) );
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
			'cli_codepage' => 'cp852',
			'cookie_file'  => __DIR__ . '/SESSION_ID',

			/* Services */
			'api'       => 'Orkan\\Filmweb\\Api\\Api',
			'tarnsport' => 'Orkan\\Filmweb\\Transport\\Curl',
			'logger'    => 'Orkan\\Filmweb\\Logger',
		);
		/* @formatter:on */
	}

	/**
	 * Get Api instance
	 *
	 * @return \Orkan\Filmweb\Api\Api
	 */
	public function getApi()
	{
		return $this->app['api'];
	}

	/**
	 * Callback for trigger_error()
	 *
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 */
	public function errorHandler( int $errno, string $errstr, string $errfile, int $errline ): bool
	{
		// Do not handle errors excluded from error_reporting() with ~ sign
		if ( ! ( error_reporting() & $errno ) ) {
			return false;
		}

		$is_filmweb = ( E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE ) & $errno;
		$msg = $is_filmweb ? 'Filmweb' : 'PHP';

		switch ( $errno )
		{
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

		$is_error = in_array( $type, array( 'error', 'warning' ) );

		$msg = "$msg $type: $errstr in $errfile on line $errline\n";

		// Print message to terminal in CLI mode, or echo it otherwise
		Utils::print( $msg, $is_error, $this->app['cfg']['cli_codepage'] );

		// Call appropriate Logger method type
		$this->app['logger']->$type( $msg );

		// Quit on error! Tip: Default PHP exit code is 255
		if ( $is_error ) {
			exit( 1 );
		}

		// Don't execute PHP internal error handler
		// return true;

		return false;
	}

	/**
	 * Create title string i.e for log entries
	 *
	 * @return string Formated self::TITLE
	 */
	public static function getTitle(): string
	{
		$u = str_repeat( '_', 33 );
		return $u . '[' . self::TITLE . ']' . $u;
	}

	/**
	 * Get time elapsed from spawning this instance
	 *
	 * @return float Elapsed time in fractional seconds
	 */
	public function getExecTime(): float
	{
		if ( null === $this->start_time ) {
			$this->start_time = microtime( true );
			return 0;
		}
		return microtime( true ) - $this->start_time;
	}
}
