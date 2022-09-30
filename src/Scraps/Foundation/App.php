<?php 
namespace Scraps\Foundation;
/**
 * 
 */
class App{

	protected $basePathRoot;
	
	public function __construct(string $path){

		$this->basePathRoot = $path;

	}
	public function make(string|array $class){
		
		$class = is_array($class) ? $class : [$class];
		
		$inst = [];

		foreach ($class as $key => $value) {

			if(is_array($value)){

				$c = isset($value[0]) ? $value[0] : $value['class'];

				$a = isset($value[1]) ? $value[1] : (isset($value['args']) ? $value['args'] : []);

				$a = is_array($a) ? $a : [$a];

				$m = isset($value[2]) ? $value[2] : (isset($value['method']) ? $value['method'] : null);

				$ret = $this->build($c, $a, $m);

				if((isset($value[3]) && is_callable($value[3])) || (isset($value['callback']) && is_callable($value['callback']))){

					$cb = isset($value[3]) ? $value[3] : $value['callback'];

					$cb($ret);

				}

				$inst[] = $ret;

			}
			else{
				$inst[] = $this->build($value);
			}
		
		
		}
		return $inst;
		
	}
	public function build($class, array $args = [], string|null|array $method = null){

		$method = is_array($method) ? $method : ($method != null ? [$method] : null);

		if(class_exists($class) && ($method === null || empty($method))){

			return new $class(...$args);

		}

		else{

			$ret = [];

			foreach ($method as $m) {

				$ret[] = $class::$m(...$args);

			}

			return $ret;

		}

	}
}