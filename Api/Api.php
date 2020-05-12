<?php

namespace Orkan\Filmweb\Api;

use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Utils;
use Orkan\Filmweb\Transport\Transport;

/**
 * Communicate with Filmweb via the given Transport object
 *
 * @author Orkan
 */
class Api
{
	/**
	 * Filmweb API constants
	 */
	const URL = 'https://ssl.filmweb.pl/api';
	const VER = '1.0';
	const APP = 'android';
	const KEY = 'qjcGhW2JnvGT9dfCt3uT_jozR3s';

	/**
	 * Slowdown() communication a bit
	 */
	private $calls = 0; // Current call #no
	private $limit_call;
	private $limit_usec;

	/**
	 * The Transport obiect given by main Filmweb class
	 *
	 * @var Transport
	 */
	private $send;

	/**
	 * API method
	 *
	 * @var string
	 */
	private $request;

	/**
	 * Response from server
	 *
	 * @var string
	 */
	private $response;

	/**
	 * First line of response from server. Usualy: ok|err
	 *
	 * @var string
	 */
	private $status;

	/**
	 * Second line of response from server
	 * On success - encoded json object
	 * On failure - exception string (exc Message...)
	 * Note: There is a third line included in response with unknown meaning ATM, like for:
	 * getFilmInfoFull: t:43200
	 * getUserFilmVotes: s
	 *
	 * @var string
	 */
	private $output;

	public function __construct( Transport $t, array $cfg )
	{
		$this->send = $t;
		$this->limit_call = $cfg['limit_call'];
		$this->limit_usec = $cfg['limit_usec'];
	}

	/**
	 * A reusable main method, to help invoke multiple Filmweb API methods within one login
	 *
	 * @param string $method Filmweb API method
	 * @param array $args Arguments to send
	 * @return string Status string extracted from servers responce @see $this->status
	 */
	public function call( string $method, array $args = [] ): string
	{
		// Clear the last method call leftovers...
		$this->request = $this->response = $this->status = $this->output = null;

		// Reduce the frequency of API calls
		$this->slowdown();

		$method = __NAMESPACE__ . '\\Method\\' . $method; // cant use the 'use' statement
		$m = new $method();

		$this->request = $m->format( $args );

		Logger::debug( $this->request );
		Logger::info( $this->request );

		$this->response = $this->send->with(
		/* @formatter:off */
			$m->getType(),
			self::URL,
			self::getQuery( $this->request )
		);
		/* @formatter:on */

		Logger::debug( 'Response: ' . $this->response );

		$r = explode( "\n", $this->response );

		if ( count( $r ) < 2 ) {
			trigger_error( 'Wrong response format', E_USER_ERROR );
		}

		$this->status = isset( $r[0] ) ? $r[0] : 'null'; // ok|err
		$this->output = $r[1];

		Logger::info( "status [{$this->status}]" );

		// Stop execution on error!
		if ( in_array( $this->status, array( 'err', 'null' ) ) ) {
			trigger_error( $this->output, E_USER_ERROR );
		}

		return $this->getStatus();
	}

	/**
	 * Collect data from the query under following keys:
	 * json - a JSON decoded object
	 * extra - an additional suffix from the response
	 * raw - raw string from the response
	 * default - an array with all of the above
	 *
	 * @param string $key json|extra|raw
	 * @return mixed Requested data
	 */
	public function getData( string $key = 'all' )
	{
		if ( 'raw' === $key ) {
			return $this->output;
		}

		$i = strrpos( $this->output, ']' );
		if ( false === $i || '[' !== $this->output[0] ) {
			trigger_error( 'No JSON getData found', E_USER_ERROR );
		}

		$i += 1;
		$data1 = substr( $this->output, 0, $i );
		$data2 = substr( $this->output, $i );

		Logger::debug( 'json_decode: ' . $data1 );
		Logger::debug( 'extra: ' . $data2 );

		$json = json_decode( $data1 );
		if ( null === $json ) {
			trigger_error( 'Decoding JSON object failed', E_USER_ERROR );
		}

		$all = array(
		/* @formatter:off */
			'json'  => $json,
			'extra' => $data2,
			'raw'   => $this->output,
		);
		/* @formatter:on */

		if ( array_key_exists( $key, $all ) ) {
			return $all[ $key ];
		}

		return $all;
	}

	/**
	 * Get query string in Filmweb API format
	 *
	 * @param string $method Filmweb API method
	 * @return string Query string
	 */
	private static function getQuery( string $method ): string
	{
		$met = $method . '\n'; // required ?!
		$out = array(
		/* @formatter:off */
			'methods'   => $met,
			'signature' => self::VER . ',' . md5( $met . self::APP . self::KEY ),
			'version'   => self::VER,
			'appId'     => self::APP,
		);
		/* @formatter:on */

		Logger::debug( Utils::print_r( $out ) );

		return http_build_query( $out );
	}

	/**
	 * Get first line of response from server: ok|err
	 *
	 * @return string Status string
	 */
	public function getStatus(): string
	{
		return $this->status;
	}

	/**
	 * Get last API method used
	 *
	 * @return string API method
	 */
	public function getRequest(): string
	{
		return $this->request;
	}

	/**
	 * Get raw response from server
	 *
	 * @return string Raw response
	 */
	public function getResponse(): string
	{
		return $this->response;
	}

	/**
	 * Sleep for [limit_usec] milisecconds between [limit_call] API calls
	 */
	private function slowdown(): void
	{
		if ( 0 == ++ $this->calls % $this->limit_call ) {
			Logger::debug( "Current Api call #{$this->calls}. Slipping for {$this->limit_usec} microseconds..." );
			usleep( $this->limit_usec );
		}
	}
}
