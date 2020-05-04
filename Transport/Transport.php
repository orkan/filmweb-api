<?php

abstract class Transport {
	abstract protected static function  get(string $url, array $args = []) : string;
	abstract protected static function post(string $url, array $args = []) : string;
	public static function with(string $send, string $url, array $args) : string {
		return $this->$send($url, $args);
	}
}