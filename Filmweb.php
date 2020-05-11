<?php

namespace Orkan\Filmweb;

use Orkan\Filmweb\Api\Api;
use Orkan\Filmweb\Api\Method\login;
use Orkan\Filmweb\Transport\Curl;

/**
 * Non-official API for Filmweb.pl
 *
 * @author Orkan
 */
class Filmweb
{
	const TITLE = 'Filmweb Api by Orkan';

	/**
	 * Options merged with defaults
	 *
	 * @var array[]
	 */
	private $cfg;

	/**
	 * Api instance
	 *
	 * @var Api
	 */
	private $api;

	/**
	 * Initialize child objects: Transport & Api
	 * Login to Filmweb
	 *
	 * @param string $login
	 * @param string $pass
	 * @param array $cfg Overrides for $this->cfg
	 */
	public function __construct( string $login, string $pass, array $cfg = [] )
	{
		$this->cfg = array_merge( array(
			/* @formatter:off */

			/* These must be set! */
			'cli_codepage' => 'cp852',
			'cookie_file'  => dirname(__FILE__) . DIRECTORY_SEPARATOR . "{$login}-cookie.txt",
			'log_channel'  => self::TITLE,
			'log_file'     => self::TITLE . '.log',
			'log_timezone' => 'UTC', // 'UTC' @see https://www.php.net/manual/en/timezones.php

			/* Leave these for \Monolog defaults or define your own in $cfg */
			'log_keep'     => 0,    // \Monolog\Handler\RotatingFileHandler->maxFiles
			'log_datetime' => null, // 'Y-m-d\TH:i:s.uP'
			'log_format'   => null, // "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"

			/* Api call limiter */
			'limit_call'   => 8,   // Calls between pauses
			'limit_usec'   => 500, // Pause duration in microseconds
		), $cfg);
		/* @formatter:on */

		// @see $this->errorHandler()
		set_error_handler( array( $this, 'errorHandler' ) );

		Logger::init( $this->cfg );
		Logger::info( self::getTitle() ); // Introduce itself! :)

		$transport = new Curl( array( 'cookie' => $this->cfg['cookie_file'] ) );
		$this->api = new Api( $transport, $this->cfg );
		$this->api->call( 'login', array( login::NICKNAME => $login, login::PASSWORD => $pass ) );
	}

	/**
	 * Save Api instance for later
	 *
	 * @return \Orkan\Filmweb\Api\Api
	 */
	public function getApi(): Api
	{
		return $this->api;
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
		if ( ! (error_reporting() & $errno) ) {
			return false;
		}

		$is_filmweb = (E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE) & $errno;
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
		Utils::print( $msg, $is_error, $this->cfg['cli_codepage'] );

		// Call appropriate Logger method type
		Logger::$type( $msg );

		// Quit on error! Tip: Default PHP exit code is -1
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
}
