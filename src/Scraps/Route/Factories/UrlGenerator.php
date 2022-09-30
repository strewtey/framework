<?php 
namespace Scraps\Route\Factories;
/**
 * 
 */
class UrlGenerator{
	public $scheme;
	
	public function __construct(){
		$this->schemeInit();
	}
	public function schemeInit(){
		$b = (isset($_SERVER['REQUEST_SCHEME']) && ($_SERVER['REQUEST_SCHEME'] == 'http' || $_SERVER['REQUEST_SCHEME'] == 'https'))
			? '://'
			: (isset($_SERVER['REQUEST_SCHEME']) && ($_SERVER['REQUEST_SCHEME'] == 'ftp')
				? ':///'
				: null
			)
		;
		$this->scheme = !is_null($b) ? $_SERVER['REQUEST_SCHEME'] . $b : 'http://';
	}
	public function current(array $params = []){
		$res = '';
		if(!empty($params)){
			$t = [];
			if($qr = isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null){
				foreach (explode('&', $qr) as $key => $value) {
					$e = explode('=', $value);
					$t[$e[0]] = $e[1];
				}
			}
			$t = array_merge($t, $params);
			$q = http_build_query($t, null, null,  PHP_QUERY_RFC3986);
			$res =  $this->scheme . $_SERVER['HTTP_HOST'] . (isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '') . (!empty($q) ? "?" . $q : '');
		}
		else $res = !is_null($this->scheme) ? $this->scheme . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : false;
		return $res;
	}
	public function previous(){
		return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
	}
	public function host($path = null, array $params = [], $get = true, bool $get_array = true){
		$gg = $this->get_i($params, $get_array);
		return $this->scheme . (isset($_SERVER['HTTP_HOST']) ? ($_SERVER['HTTP_HOST'] . (is_null($path) ? null : '/' . $path)) : false) . ($get === true && !empty($gg) ? '?' . $gg : null);
	}
	private function get_i(array|null $params = [], bool $array_ = true){
		$params = is_null($params) ? [] : $params;
		$res = null;
		$__get = $_GET;
		if((isset($__get) && !empty($__get)) || !empty($params)){
			foreach ($params as $key => $value) {
				if(isset($__get[$key]) && $array_ === true){
					if(is_array($__get[$key])) $__get[$key][] = $value;
					else $__get[$key] = [$__get[$key], $value];
				}
				else $__get[$key] = $value;
			}
			$res = http_build_query($__get, null, null,  PHP_QUERY_RFC3986);
		}
		return $res;
	}
}
// Route::urlGenerator()->ff()