<?php

abstract class Method {
	abstract protected function getRequest() : string;
	abstract protected function getResponse(string $response) : array;
	protected function className() : string {
		$parts = explode('\\', get_class($this));
		return array_pop($parts);
	}
}
