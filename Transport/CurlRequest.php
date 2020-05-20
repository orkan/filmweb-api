<?php

namespace Orkan\Filmweb\Transport;

/**
 * Class to abstract the usage of the cURL functions for testing purposes
 *
 * @see https://stackoverflow.com/questions/7911535/how-to-unit-test-curl-call-in-php
 * @author Orkan
 *
 */
class CurlRequest
{
	private $handle = null;

	public function init( $url = '' )
	{
		$this->handle = curl_init( $url );
	}

	public function setOpt( $name, $value )
	{
		curl_setopt( $this->handle, $name, $value );
	}

	public function setOptArray( $array )
	{
		curl_setopt_array( $this->handle, $array );
	}

	public function exec()
	{
		return curl_exec( $this->handle );
	}

	public function getInfo( $opt = null )
	{
		return curl_getinfo( $this->handle, $opt );
	}

	public function close()
	{
		curl_close( $this->handle );
	}
}
