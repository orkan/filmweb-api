<?php

final class Api {
	
	private $keys;
	private $data;
	private $extra;
	
	private Transport $send;

	public function __construct(Transport $t) {
		$this->send = $t;
	}
	
	public function method($method, $args = []) {
		$m = new $method($args);
		$this->send->with($m->getType(), $m->getUrl(), $m->getArgs());

		
	}
	
	protected function getData($response) {
		$a = explode("\n", $response);

		if('ok' == $a[0])
		{
			/*
			 * Znajdz dane w stringu opdpowiedzi. Format:
			 * [tablica [zagniezdzona]] t:12345|s
			 * ^^^^^^^ data ^^^^^^^^^^^^^^extra^^
			 */
			$i = strrpos($a[1], ']') + 1; // znajdz koniec tablicy
			$data  = substr($a[1], 0, $i);
			$extra = substr($a[1], $i);
			
			$raw = json_decode($data);
			
			array_combine($this->keys, )
			
			return json_decode($this->data);
		}
		else
		{
			throw new Exception('Filmweb API error: ' . $a[1]);
		}

		return $a;
	}
	
	protected function mapKeys($arr) {
		
		
		foreach($this->keys as $i => $key)
			$data[$key] = $arr[$i];

		return $data;
	}
	
	public function execute(Transport $request) {

	}
	
	private function signature($method) {
		$met = $method . '\n'; // api wymaga \n na koncu
		$ver = '1.0';
		$app = 'android';
		$key = 'qjcGhW2JnvGT9dfCt3uT_jozR3s';
		$arr = [
			'methods'   => $met,
			'signature' => $ver . ',' . md5($met . $app . $key),
			'version'   => $ver,
			'appId'     => $app,
		];
		return $arr;
		//return http_build_query($arr);
		//return urldecode( http_build_query($arr) );
	}
}
