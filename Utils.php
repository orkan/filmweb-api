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
	 * Format byte size string
	 * Examples: 361 bytes | 1016.1 kB | 14.62 Mb | 2.81 GB
	 *
	 * @param int $bytes Size in bytes
	 * @return string Byte size string
	 */
	public static function formatBytes( int $bytes = 0 ): string
	{
		$sizes = array( 'bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
		return $bytes ? ( round( $bytes / pow( 1024, ( $i = floor( log( $bytes, 1024 ) ) ) ), $i > 1 ? 2 : 1 ) . ' ' . $sizes[$i] ) : '0 ' . $sizes[0];
	}

	/**
	 * Format time
	 *
	 * @param float $seconds Time in fractional seconds
	 * @param bool $fractions Add fractions part?
	 * @return string Time in format 18394d 16g 11m 41.589s
	 */
	public static function formatTime( float $seconds, bool $fractions = true ): string
	{
		$d = $h = $m = 0;
		$s = (int) $seconds; // truncate fraction
		$u = round( $seconds - $s, 3 ); // truncate int and round

		if ( $s >= 86400 ) {
			$d = floor( $s / 86400 );
			$s = floor( $s % 86400 );
		}
		if ( $s >= 3600 ) {
			$h = floor( $s / 3600 );
			$s = floor( $s % 3600 );
		}
		if ( $s >= 60 ) {
			$m = floor( $s / 60 );
			$s = floor( $s % 60 );
		}
		$f = $fractions ? $u + $s : $s;
		return trim( ( $d ? "{$d}d " : '' ) . ( $h ? "{$h}g " : '' ) . ( $m ? "{$m}m " : '' ) . ( $f ? "{$f}s" : '' ) );
	}

	/**
	 * Last 3 digits in timestamp string returned by filmweb are fractions
	 * Cut it off!
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
	 * @param string $timestamp Must be string to allow over 32bit numbers (e.g. 1588365133974) returned by Filmweb
	 * @param string $format Date format. Use constants i.e. DATE_COOKIE or string 'Y-m-d H:i:s.u e'
	 * @param string $timezone
	 * @return string Date string
	 */
	public static function formatDateTimeZone( string $timestamp, $format = DATE_RSS, $timezone = 'Europe/Berlin' ): string
	{
		$t = self::getTimestamp( $timestamp );
		return ( new \DateTime( null, ( new \DateTimeZone( $timezone ) ) ) )->setTimestamp( $t )->format( $format );
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
	 * Print message to standard output or STDERR if in CLI mode
	 * Notes:
	 * STDOUT and echo both seems to work in CLI
	 * STDERR is buffered and displays last
	 * @codeCoverageIgnore
	 *
	 * @param string $message
	 * @param bool $is_error Choose the right I/O stream for outputing errors
	 * @param string $codepage
	 */
	public static function print( string $message, bool $is_error = false, string $codepage = 'cp852' ): void
	{
		if ( 'cli' === php_sapi_name() ) {
			fwrite( $is_error ? STDERR : STDOUT, iconv( 'utf-8', $codepage, $message ) );
		} else {
			echo $message;
		}
	}

	/**
	 * Print message to STDERR
	 * @codeCoverageIgnore
	 *
	 * @param string $message
	 * @param string $codepage
	 */
	public static function stderr( string $message, string $codepage = 'cp852' ): void
	{
		self::print( $message, true, $codepage );
	}
}
