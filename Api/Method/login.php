<?php


final class login extends Method {
	private $type = 'post';
	private $args = ['username', 'password'];
	private $prop;
	//private $keys = ['username', 'avatar', 'name', 'userId', 'gender'];
	
	public function __construct(array $args) {
		$this->prop = array_combine($this->args, $args);
	}
	
	public function request() : string {
		return sprintf('%s ["%s", "%s", 1]', $this, $this->prop['username'], $this->prop['password']);
	}
	
	public function getResponse($response) : array {
		return [];
	}
}
