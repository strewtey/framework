<?php
namespace Scraps\Route\Factories;

/**
 * 
 */
class Domain {

	public $host;
	
	public function __construct($route, string|array $url) {
		$url = is_array($url) ? $url : [$url];
		$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
		$this->host = in_array($host, $url);
	}
	public function group(callable $callback){
		if($this->host)
			call_user_func($callback);
	}
}
