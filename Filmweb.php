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
	 * Save credentials from constructor
	 *
	 * @var string Login
	 */
	private $login;

	/**
	 * Save credentials from constructor
	 *
	 * @var string Password
	 */
	private $pass;

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

		$this->login = $login;
		$this->pass = $pass;

		// Create Dependency Injection Container
		$this->app = new Container();

		// Merge configuration with defaults
		$this->app['cfg'] = array_merge( $this->getDefaults(), $config );

		// Set Error Handler as soon as possible! (DI property)
		// @see $this->errorHandler()
		$this->app['errorHandler'] = array( $this, 'errorHandler' );
		set_error_handler( $this->app['errorHandler'] );

		// Create application services
		// @codeCoverageIgnoreStart
		$this->app['logger'] = function ( $c ) {
			return new $this->app['cfg']['logger']( $c );
		};
		$this->app['send'] = function ( $c ) {
			return new $this->app['cfg']['tarnsport']( $c );
		};
		$this->app['request'] = function ( $c ) {
			return new $this->app['cfg']['request']( $c );
		};
		$this->app['api'] = function ( $c ) {
			return new $this->app['cfg']['api']( $c );
		};
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Get default config
	 *
	 * @return array Default config
	 */
	private function getDefaults()
	{
		/* @formatter:off */
		return array(
			'cli_codepage' => 'cp852',
			'cookie_file'  => dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'SESSION_ID',
			'is_debug'     => false,

			/* Services */
			'api'       => 'Orkan\\Filmweb\\Api\\Api',
			'tarnsport' => 'Orkan\\Filmweb\\Transport\\Curl',
			'request'   => 'Orkan\\Filmweb\\Transport\\CurlRequest',
			'logger'    => 'Orkan\\Filmweb\\Logger',
		);
		/* @formatter:on */
	}

	/**
	 * Login to Filmweb
	 * Return Api instance
	 *
	 * @return \Orkan\Filmweb\Api\Api
	 */
	public function getApi()
	{
		// On first call to Api Login to filmweb.pl
		if ( ! $this->app['api']->getTotalCalls() ) {
			$this->app['logger']->info( self::getTitle() ); // Introduce itself! :)
			$this->app['api']->call( 'login', array( login::NICKNAME => $this->login, login::PASSWORD => $this->pass ) );
		}

		return $this->app['api'];
	}

	/**
	 * Return Logger instance
	 *
	 * @return \Orkan\Filmweb\Logger
	 */
	public function getLogger()
	{
		return $this->app['logger'];
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
		// Handle errors included in error_reporting() only
		if ( error_reporting() & $errno ) {
			$is_filmweb = ( E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE ) & $errno;
			$msg = $is_filmweb ? 'Filmweb' : 'PHP';

			switch ( $errno )
			{
				// @codeCoverageIgnoreStart
				case E_ERROR:
				case E_USER_ERROR:
					$type = 'error';
					break;

				case E_WARNING:
				case E_USER_WARNING:
					$type = 'warning';
					break;
				// @codeCoverageIgnoreEnd

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
			if ( $is_error && ! defined( 'TESTING' ) ) {
				// @codeCoverageIgnoreStart
				exit( 1 );
				// @codeCoverageIgnoreEnd
			}
		}

		return false; // Don't execute PHP internal error handler
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
