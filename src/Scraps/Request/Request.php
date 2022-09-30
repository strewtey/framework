<?php
namespace Scraps\Request;
use App\Https\HttpRequest;

use ReflectionFunction;
use ReflectionUnionType;
use ReflectionNamedType;
/**
 * 
 */
class Request{

	public static $named;
	protected $name;
	protected $path;
	protected $action;
	protected $request;
	protected $params = [];

	public function __construct(string $path, string|array|callable $action){
		$this->path = trim($path, '/');
		$this->request = new HttpRequest();
		$this->action = $action;
	}
	public function name($name){
		if(isset(static::$named[$name])){
			die("Name already exists !");
		}
		else{
			$this->name = $name;
			static::$named[$name] = url($this->path, [], false);
		}
	}
	public function getName() : string{ return $this->name; }
	public function getPath(){ return $this->path; }
	public function getAction(){ return $this->action; }
	public function match(string $url){
		preg_match('/(.*)\?(.*)/', $this->path, $ver);
		$pathToMatch = null;
		if(empty($ver)){
			$pattern = ['#({[\w]+})#', '([^/]+)'];
			$path = preg_replace($pattern[0], $pattern[1], $this->path);
			$pathToMatch = "#^" . str_replace('/', '\/', $path) . "$#";
		}
		else{
			$this->path = $ver[1] . '}';
			$pattern = ['/({.*?})/', '(.*)'];
			$path = preg_replace($pattern[0], $pattern[1], $this->path);
			$pathToMatch = "/" . str_replace('/', '\/', $path) . "/";
		}
		if(preg_match($pathToMatch, $url, $matches)){
			header('HTTP/1.1 200 OK');
			preg_match_all('/{(.*?)}/', $this->path, $key);
			$key = end($key);

			array_shift($matches);
			if(count($key) == count($matches)){
				$this->params = [];
				foreach ($key as $ke => $k) {
					$this->params[$k] = $matches[$ke];
				}
			}
			else
				$this->params = $matches;
			return true;
		}
		return false;
	}
	public function execute(){
		$name = null;
		if(!is_array($this->action) && is_callable($this->action)){
			$injection = (function($method, $classes){
				$res = [];
				foreach ((new ReflectionFunction($method))->getParameters() as $i => $param){
					if(is_object($param->getType()) && ($param->getType()::class == "ReflectionUnionType" || $param->getType()::class == "ReflectionNamedType")){
						$p = ($param->getType()::class == "ReflectionUnionType") ? $param->getType()->getTypes() : [$param];
						foreach ($p as $key => $obj){
							$f = false;
							foreach ($classes as $namespace_class => $value) {
								if($namespace_class == $obj->getName()){
									$res[$param->getName()] = $value;
									$f = true;
								}
								if($f) break;
							}
						}
					}
				}
				return empty($res) ? false : $res;
			})($this->action, ['Scraps\Request\Request' => $this]);
			$this->params = is_array($injection) ? array_merge($this->params, $injection) : $this->params;
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				call_user_func($this->action, ...$this->params);
				return true;
			}
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				call_user_func($this->action, $this->request, ...$this->params);
				return true;
			}
		}
		$action = is_array($this->action) ? $this->action : explode('@', $this->action);
		if(method_exists($action[0], $action[1]) || method_exists($action[0], 'staticCallable')){
			$cons = isset($action[2]) && ($action[2] === true || $action[2] == 'const' || $action[2] == 'construct');
			$controller = $cons === true && !empty($this->params) ? new $action[0](...$this->params) : new $action[0]();
			$method = $action[1];
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				if(!empty($this->params)) return $cons === false ? $controller->$method(...$this->params) : $controller->$method();
				else return $controller->$method();
			}
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				return isset($this->params) 
					? $controller->$method($this->request, implode($this->params)) 
					: $controller->$method($this->request);
			}
		}
		else {
			header('HTTP/1.1 303 Error !');
			echo "Class not exists !";
			die();
		}
	}
}