<?php

namespace Orkan\Filmweb\Api\Method;

abstract class Method
{
	abstract protected function format(array $args) : string;

	public function getType() : string
	{
		return static::TYPE;
	}

	function __toString() : string
	{
		$parts = explode('\\', get_class($this));
		return array_pop($parts);
	}
}
