<?php
namespace Scraps\Route;
use Scraps\Request\Request;
use Scraps\Http\Error;
use Scraps\Factory\Factory;
/**
 * 
 */
class Route extends Factory{
	public $request;
	public static $domains;
	public static $fallback;
	public static $error;
	private static $current_request;
	
	public function __construct(){}
	public function get(string|array $path, string|callable|array $action){
		// $routes = null;
		// $routes = new Request($path, $action);
		// $this->request['GET'][] = $routes;
		// return $routes;
		$path = is_array($path) ? $path : [$path];
		$path = count($path) > 1 ? array_reverse($path) : $path;
		$routes = null;
		foreach ($path as $key => $value) {
			$routes = new Request($value, $action);
			$this->request['GET'][] = $routes;
		}
		return $routes;
	}
	public function post(string|array $path, string|callable|array $action){
		$path = is_array($path) ? $path : [$path];
		$routes = null;
		foreach ($path as $key => $value) {
			$routes = new Request($value, $action);
			$this->request['POST'][] = $routes;
		}
		return $routes;
	}
	protected function called($class, array|null $args = null, string|null $key = null){
		if(is_null($args)) $args = [];
		return new $class($this, ...$args);
	}
	protected function getFactorySpace(){return 'Scraps\Route\Factories\\';}
	public function run(){
		header('HTTP/1.0 404 PAGE NOT FOUND !');
		$res = false;
		if(isset($this->request[strtoupper($_SERVER['REQUEST_METHOD'])])){
			foreach ($this->request[strtoupper($_SERVER['REQUEST_METHOD'])] as $key => $route) {
				$u = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['SCRIPT_NAME']) ? trim($_SERVER['SCRIPT_NAME'], 'index.php') : '/'));
				if($res = $route->match(trim($u, '/'))){
					self::$current_request = $route;
					$route->execute();
					break;
				}
			}
		}
		Error::init();
	}
	public function named($name, $datas = [], $get = true){
		$gt = '';
		if(isset(Request::$named[$name])){
			if(isset($_GET) && !empty($_GET)){
				foreach ($_GET as $key => $value) {
					if(is_array($value)){
						foreach ($value as $k => $v) {
							$gt .= (empty($gt) ? '?' : '&') . $key . '[' . $k . ']=' . $v;
						}
					}
					else $gt .= (empty($gt) ? '?' : '&') . $key . '=' . $value;
				}
			}
			$gt = $get === true ? $gt : '';
			$url = Request::$named[$name];
			if(!preg_match('/{(.*?)}/', $url)){
				if(is_array($datas) && !empty($datas)){
					foreach ($datas as $key => $value) {
						$gt .= (empty($gt) ? '?' : '&') . $key . '=' . $value;
					}
				}
				return $url . $gt;
			}
			else{
				// Error
				if(empty($datas)) return null;
				foreach ($datas as $key => $value) {
					if(preg_match('/{' . $key . '}/', $url)) $url = str_replace('{' . $key . '}', $value, $url);
					else $gt .= (empty($gt) ? '?' : '&') . $key . '=' . $value;
				}
				if(preg_match('/{(.*?)}/', $url)) return null;
				$gt = trim($gt, '&');
				$url .= $gt;
				return $url;
			}
		}
		return null;
	}
	public static function getCurrentRequest(){
		return self::$current_request;
	}
	public function fallback($callback){
		static::$fallback = $callback;
	}
	public function redirect($link){
		header('Location: ' . $link);
	}
	public function error(int|string $code, callable $callback){
		static::$error[$code] = $callback;
	}

	// public static function run(){
	// 	foreach ($this->request[$_SERVER['REQUEST_METHOD']] as $key => $route) {
	// 		$u = isset($_GET['url']) ? $_GET['url'] : '';
	// 		if($route->match(trim($u, '/'))) {
	// 			$route->execute();
	// 			// die();
	// 			break;
	// 		}
	// 		header('HTTP/1.0 404 PAGE NOT FOUND !');
	// 	}
	// }
}