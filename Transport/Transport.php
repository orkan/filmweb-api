<?php

namespace Orkan\Filmweb\Transport;

/**
 * A skeleton class (Interface) for transport method
 *
 * @author Orkan
 */
abstract class Transport
{
	protected const USERAGENT = 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3';
	protected const CONNECTTIMEOUT = 5;
	protected const TIMEOUT = 5;
	protected const RETURNTRANSFER = true;
	protected const SSL_VERIFYPEER = false;
	protected const SSL_VERIFYHOST = false;

	/**
	 * Do [get] http request
	 *
	 * @param string $url
	 * @param string $query
	 * @return string Response from the server
	 */
	abstract protected function get( string $url, string $query ): string;

	/**
	 * Do [post] http request
	 *
	 * @param string $url
	 * @param string $query
	 * @return string Response from the server
	 */
	abstract protected function post( string $url, string $query ): string;

	/**
	 * Choose the right method to send http request
	 *
	 * @param string $send Method get|post
	 * @param string $url
	 * @param string $query
	 * @return string Response from the server
	 */
	public function with( string $send, string $url, string $query ): string
	{
		return $this->$send( $url, $query );
	}

	/**
	 * Get total request time
	 *
	 * @return float Total request time
	 */
	abstract protected function getTotalTime(): float;

	/**
	 * Get total data sent
	 *
	 * @return int Total data sent in bytes
	 */
	abstract protected function getTotalDataSent(): int;

	/**
	 * Get total data recived from server
	 *
	 * @return int Total data recived in bytes
	 */
	abstract protected function getTotalDataRecived(): int;
}
