<?php

namespace Orkan\Filmweb;

/**
 * Helper functions
 *
 * @author Orkan
 */
class Utils
{

	/**
	 * If the timestamp returned by filmweb is 3 digits too long?!? ...
	 * Cut it!
	 *
	 * @param string $timestamp I.e. 1588365133974
	 * @return string I.e. 1588365133
	 */
	public static function getTimestamp( string $timestamp ): string
	{
		$l = strlen( time() );
		return strlen( $timestamp ) > $l ? substr( $timestamp, 0, $l ) : $timestamp;
	}

	/**
	 * Format date acording to current time zone
	 *
	 * @param string $timestamp Must be string to allow over 32bit numbers (i.e. 1588365133974) returned by Filmweb
	 * @param string $format Date format. Use constants i.e. DATE_COOKIE or string 'Y-m-d H:i:s.u e'
	 * @param string $timezone
	 * @return string Date string
	 */
	public static function formatDateTimeZone( string $timestamp, $format = DATE_RSS, $timezone = 'Europe/Berlin' ): string
	{
		$t = self::getTimestamp( $timestamp );
		return (new \DateTime( null, (new \DateTimeZone( $timezone )) ))->setTimestamp( $t )->format( $format );
	}

	/**
	 * Remove double spaces from PHP::print_r()
	 *
	 * @param array $array
	 * @return string
	 */
	public static function print_r( array $array ): string
	{
		$str = print_r( $array, true );
		return preg_replace( '/[ ]{2,}/', '', $str );
	}

	/**
	 * Print message to STDERR if in CLI mode
	 *
	 * @param string $msg
	 */
	public static function print( string $message, $codepage = 'cp852' ): void
	{
		if ( 'cli' === php_sapi_name() ) {
			fwrite( STDERR, iconv( 'utf-8', $codepage, $message ) );
		} else {
			echo $message;
		}
	}
}
