<?php

namespace Orkan\Filmweb\Api;

use Orkan\Filmweb\Utils;
use Pimple\Container;

/**
 * Communicate with Filmweb via the given Transport object
 *
 * @author Orkan
 */
class Api
{
	/**
	 * Slowdown() statistics
	 */
	private $calls = 0; // Current call no.
	private $limit_total = 0; // Total sleep time in microseconds

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
	 * First line of response from server, usualy: ok|err
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
			'methods_ns' => __NAMESPACE__ . '\\Method\\', // Methods namespace used

			/* Filmweb API constans */
			'api_url' => 'https://ssl.filmweb.pl/api',
			'api_ver' => '1.0',
			'api_app' => 'android',
			'api_key' => 'qjcGhW2JnvGT9dfCt3uT_jozR3s',

			'limit_call' => 8, // usleep() after reaching this limit of call()'s
			'limit_usec' => 300000, // In microseconds! 1s == 1 000 000 us
		);
		/* @formatter:on */
	}

	/**
	 * Retrive API method from continer or create one
	 *
	 * @param string $method API method name
	 * @return API method instance
	 */
	private function getMethod( string $method )
	{
		if ( ! $this->app->offsetExists( $method ) ) {

			$m = $this->app['cfg']['methods_ns'] . $method;

			$this->app[$method] = function () use ($m ) {
				return new $m();
			};
		}

		return $this->app[$method];
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

		$m = $this->getMethod( $method );
		$this->request = $m->format( $args );

		$this->app['logger']->debug( $this->request );
		$this->app['logger']->info( $this->request );

		$this->response = $this->app['send']->with(
		/* @formatter:off */
			$m->getType(),
			$this->app['cfg']['api_url'],
			$this->getQuery( $this->request )
		);
		/* @formatter:on */

		$this->app['logger']->debug( 'Response: ' . $this->response );

		$r = explode( "\n", $this->response );

		if ( count( $r ) < 2 ) {
			trigger_error( 'Wrong response format', E_USER_ERROR );
		}

		$this->status = isset( $r[0] ) ? $r[0] : 'null'; // ok|err
		$this->output = $r[1];

		$this->app['logger']->info( "status [{$this->status}]" );

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

		$this->app['logger']->debug( 'json_decode: ' . $data1 );
		$this->app['logger']->debug( 'extra: ' . $data2 );

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
			return $all[$key];
		}

		return $all;
	}

	/**
	 * Get query string in Filmweb API format
	 *
	 * @param string $method Filmweb API method
	 * @return string Query string
	 */
	private function getQuery( string $method ): string
	{
		$met = $method . '\n'; // required ?!
		$out = array(
		/* @formatter:off */
			'methods'   => $met,
			'signature' => $this->app['cfg']['api_ver'] . ',' . md5( $met . $this->app['cfg']['api_app'] . $this->app['cfg']['api_key'] ),
			'version'   => $this->app['cfg']['api_ver'],
			'appId'     => $this->app['cfg']['api_app'],
		);
		/* @formatter:on */

		$this->app['logger']->debug( Utils::print_r( $out ) );

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
	 * Sleep for [limit_usec] microseconds between each [limit_call] calls()
	 */
	private function slowdown(): void
	{
		if ( 0 == ++ $this->calls % $this->app['cfg']['limit_call'] ) {
			$this->app['logger']->debug( "Current Api call #{$this->calls}. Sleeping for " . round( $this->app['cfg']['limit_usec'] / 1000000, 3 ) . " seconds..." );

			usleep( $this->app['cfg']['limit_usec'] );
			$this->limit_total += $this->app['cfg']['limit_usec'];
		}
	}

	/**
	 * Get total sleep time between request call()'s
	 *
	 * @return float Total sleep time in fractional seconds
	 */
	public function getTotalSleep(): float
	{
		return $this->limit_total / 1000000;
	}

	/**
	 * Get total connection time
	 *
	 * @return float Total connection time in fractional seconds
	 */
	public function getTotalTime(): float
	{
		return $this->send->getTotalTime();
	}

	/**
	 * Get total data sent
	 *
	 * @return int Total data sent in bytes
	 */
	public function getTotalDataSent(): int
	{
		return $this->send->getTotalDataSent();
	}

	/**
	 * Get total data recived from server
	 *
	 * @return int Total data recived in bytes
	 */
	public function getTotalDataRecived(): int
	{
		return $this->send->getTotalDataRecived();
	}
}
