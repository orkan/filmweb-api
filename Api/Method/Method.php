<?php

namespace Orkan\Filmweb\Api\Method;

abstract class Method
{
	abstract protected function prepare(array $args) : string;
	abstract protected function extract(array $data) : array;

	public function type() : string
	{
		return static::TYPE;
	}

	function __toString() : string
	{
		$parts = explode('\\', get_class($this));
		return array_pop($parts);
	}
}
