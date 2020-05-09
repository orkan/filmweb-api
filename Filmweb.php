<?php

namespace Orkan\Filmweb;

use Orkan\Filmweb\Api\Api;
use Orkan\Filmweb\Api\Method\login;
use Orkan\Filmweb\Transport\Curl;

/**
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
	 * Login to Filmweb during object creation Set defaults for child objects here!
	 *
	 * @param string $login
	 * @param string $pass
	 * @param array $cfg Overrides for $this->defaults
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

			/* Leave these for \Monolog to use setDefault or define your own in $cfg */
			'log_keep'     => 0,	// RotatingFileHandler->maxFiles
			'log_datetime' => null, // 'Y-m-d\TH:i:s.uP'
			'log_format'   => null, // "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"

			/* Api call limiter */
			'limit_call'   => 8,   // Calls between pauses
			'limit_usec'   => 500, // Pause duration in microseconds
		), $cfg);
		/* @formatter:on */

		set_error_handler( [$this, 'errorHandler'] ); // Filmweb->errorHandler()

		Logger::init( $this->cfg );
		Logger::info( self::getTitle() ); // Introduce itself! :)

		$transport = new Curl( ['cookie' => $this->cfg['cookie_file']] );
		$this->api = new Api( $transport, $this->cfg );
		$this->api->call( 'login', [login::NICKNAME => $login, login::PASSWORD => $pass] );
	}

	/**
	 * Keep the Api instance for future use
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
		// Handle errors defined in error_reporting() only
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

		$msg = "$msg $type: $errstr in $errfile on line $errline\n";

		// Print message to terminal in CLI mode, or echo it otherwise
		Utils::print( $msg, $this->cfg['cli_codepage'] );

		// Call appropriate Logger method type
		Logger::$type( $msg );

		// Quit on error!
		if ( in_array( $type, array('error', 'warning') ) ) {
			exit( 1 );
		}

		// Don't execute PHP internal error handler
		// return true;

		return false;
	}

	/**
	 * Get title string for log file header entry
	 *
	 * @return string Formated library title
	 */
	public static function getTitle(): string
	{
		$u = str_repeat( '_', 33 );
		return $u . '[' . self::TITLE . ']' . $u;
	}
}
