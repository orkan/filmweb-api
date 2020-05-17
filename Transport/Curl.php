<?php

namespace Orkan\Filmweb\Transport;

use Orkan\Filmweb\Utils;
use Pimple\Container;

/**
 * Curl http transport implementation
 *
 * @author Orkan
 */
class Curl extends Transport
{
	private $defaults = array(
	/* @formatter:off */
		CURLOPT_USERAGENT      => self::USERAGENT,
		CURLOPT_CONNECTTIMEOUT => self::CONNECTTIMEOUT,
		CURLOPT_TIMEOUT        => self::TIMEOUT,
		CURLOPT_RETURNTRANSFER => self::RETURNTRANSFER,
		CURLOPT_SSL_VERIFYPEER => self::SSL_VERIFYPEER,
		CURLOPT_SSL_VERIFYHOST => self::SSL_VERIFYHOST,
	);
	/* @formatter:on */

	/**
	 * Statistics
	 */
	private $total_time = 0;
	private $total_data_sent = 0;
	private $total_data_recived = 0;

	/**
	 * Dependency Injection Container
	 *
	 * @var Container
	 */
	private $app;

	/**
	 *
	 * @param array $args
	 */
	public function __construct( Container $app )
	{
		$this->app = $app;

		/* @formatter:off */
		$this->defaults[ CURLOPT_COOKIEJAR ]  = $this->app['cfg']['cookie_file'];
		$this->defaults[ CURLOPT_COOKIEFILE ] = $this->app['cfg']['cookie_file'];
		/* @formatter:on */

		$this->app['logger']->debug( Utils::print_r( $this->defaults ) );
	}

	/**
	 * Do [get] http request
	 *
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Transport\Transport::get()
	 */
	public function get( string $url, string $query ): string
	{
		$options = array(
		/* @formatter:off */
			CURLOPT_URL => $url . '?' . $query,
		);
		/* @formatter:on */

		$this->app['logger']->debug( Utils::print_r( $options ) );
		return $this->exec( $options );
	}

	/**
	 * Do [post] http request
	 *
	 * {@inheritdoc}
	 * @see \Orkan\Filmweb\Transport\Transport::post()
	 */
	public function post( string $url, string $query ): string
	{
		$options = array(
		/* @formatter:off */
			CURLOPT_URL        => $url,
			CURLOPT_POST       => true,
			CURLOPT_POSTFIELDS => urldecode( $query ),
		);
		/* @formatter:on */

		$this->app['logger']->debug( Utils::print_r( $options ) );
		return $this->exec( $options );
	}

	/**
	 * Do http request
	 *
	 * @param array $options
	 * @return string Response from the server
	 */
	private function exec( array $options ): string
	{
		$request = curl_init();
		curl_setopt_array( $request, $options + $this->defaults );
		$response = curl_exec( $request );
		$info = curl_getinfo( $request );
		curl_close( $request );

		// Grab some statistics
		// @see https://www.php.net/manual/en/function.curl-getinfo.php
		$this->total_time += $info['total_time']; // float, in fractional seconds
		$this->total_data_sent += $info['header_size'] + $info['request_size'];
		$this->total_data_recived += $info['size_download']; // @todo: Missing response headers size

		if ( false === $response ) {
			trigger_error( 'No response from server. Please check online connection', E_USER_ERROR );
		}

		return $response;
	}

	/**
	 * Get total request time
	 *
	 * @return float Total request time in fractional seconds
	 */
	public function getTotalTime(): float
	{
		return $this->total_time;
	}

	/**
	 * Get total data sent
	 *
	 * @return int Total data sent in bytes
	 */
	public function getTotalDataSent(): int
	{
		return $this->total_data_sent;
	}

	/**
	 * Get total data recived from server
	 *
	 * @return int Total data recived in bytes
	 */
	public function getTotalDataRecived(): int
	{
		return $this->total_data_recived;
	}
}
