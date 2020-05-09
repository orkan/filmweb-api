<?php

namespace Orkan\Filmweb\Transport;

use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Utils;

/**
 * Curl http transport implementation
 *
 * @author Orkan
 */
final class Curl extends Transport
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
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] )
	{
		/* @formatter:off */
		$this->defaults[ CURLOPT_COOKIEJAR ]  = $args[ 'cookie' ];
		$this->defaults[ CURLOPT_COOKIEFILE ] = $args[ 'cookie' ];
		/* @formatter:on */

		Logger::debug( Utils::print_r( $this->defaults ) );
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

		Logger::debug( Utils::print_r( $options ) );
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

		Logger::debug( Utils::print_r( $options ) );
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
		curl_close( $request );
		return $response;
	}
}
