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
	 * Saved credentials from constructor
	 *
	 * @var string Login
	 */
	private $login;

	/**
	 * Saved credentials from constructor
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
	 * Initialize services (LAZY)
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

		// Set Error Handler as soon as possible!
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
	 * Tip:
	 * See also other services for default config values.
	 * All these can be replaced by array passed to constuctor
	 *
	 * @return array Default config
	 */
	private function getDefaults()
	{
		/* @formatter:off */
		return array(
			'cli_codepage' => 'cp852',
			'cookie_file'  => dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'SESSION_ID',
			'exit_on'      => E_ERROR | E_USER_ERROR,
			'is_debug'     => false,

			/* Services */
			'api'       => 'Orkan\\Filmweb\\Api\\Api',
			'tarnsport' => 'Orkan\\Filmweb\\Transport\\Curl',
			'request'   => 'Orkan\\Filmweb\\Transport\\CurlRequest',
			'logger'    => 'Orkan\\Filmweb\\Logger',

			/* Hide sensitive log data */
			'logger_mask' => array( 'search' => array( $this->pass ), 'replace' => array( '***' ) ),
		);
		/* @formatter:on */
	}

	/**
	 * Login to Filmweb on first call only, then use cookie on subsequent calls
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
	 * PHP Error callback for trigger_error()
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
			$is_error = ( E_ERROR | E_USER_ERROR | E_WARNING | E_USER_WARNING ) & $errno;
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

			$msg = "$msg $type: $errstr";

			if ( $this->app['cfg']['is_debug'] ) {
				$msg .= " in $errfile on line $errline";
			}

			$msg .= "\n";

			// Print message to terminal in CLI mode, or echo it otherwise
			Utils::print( $msg, $is_error, $this->app['cfg']['cli_codepage'] );

			// Call appropriate Logger method type
			$this->app['logger']->$type( $msg );

			// Quit on defined error level
			// Use this to prevent exiting on unit testing
			// @tip Default PHP exit code is 255
			if ( $this->app['cfg']['exit_on'] & $errno ) {
				// @codeCoverageIgnoreStart
				exit( 1 );
				// @codeCoverageIgnoreEnd
			}
		}

		// Don't execute PHP internal error handler
		return true;
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
