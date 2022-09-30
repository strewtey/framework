<?php
namespace Scraps\Factory;
use Support\Dir;
use Support\File;
/**
 * 
 */
class Factory{
	protected static $instances;
	public function __call($class, $args){
		$c = $this->classSpace($class);
		if(class_exists($c)) return $this->called($c, $args, $class);
		if(!empty($this->alternate())) return $this->initAlternate($class, $args);
		die("This method \"" . $class . "\" not exists !");
		// $method .= 's';
		// return $this->$method(...$args);
	}
	protected function called($class, array|null $args = null, string|null $key = null){
		if(is_null($args)) $args = [];
		if($this->singleton_factory() === true && $key !== null){
			$key = strtolower($key);
			static::$instances[$key] = isset(static::$instances[$key]) ? static::$instances[$key] : new $class(...$args);
			$ins = static::$instances[$key];
		}
		else $ins = new $class(...$args);
		return $ins;
	}


	protected function singleton_factory(){
		return false;
	}

	public function classSpace(string $class, Closure|null $fun = null) : string|bool {
		$res = false;
		if(!is_null($fun) && is_callable($fun)){
			$args = ['namespace' => $this->getFactorySpace(), 'class' => $class];
			$res = $fun(...$args);
			$res = is_string($res) ? $res : false;
		}
		else
			$res = $this->getFactorySpace() . ucfirst(strtolower($class));
		return $res;
	}

	protected function exist(string $class, string|null $method = null){

		$c = $this->classSpace($class);

		return is_null($method) ? class_exists($c) : method_exists($c, $method);

	}
	protected function calledMethod($class, $method, $args, $argsComp = true, bool $verif = false, string|bool $instance_key = null){
		// $class = $this->classSpace($class);
		$ver = false;
		$res = [['dwuh82e27t&???&?W!T?&@T@&@*&#@&#F@&?E@UQY']];
		if(method_exists($class, $method)){
			if(is_callable($class . '::' . $method)){
				$m = $class . '::' . $method;
				if($argsComp === true) $res = $m(...$args);
				else $res = $m($args);
				$ver = true;
			}
			else{
				$a = $this->called($class, null, $instance_key);
				if($argsComp === true) $res = $a->$method(...$args);
				else $res = $a->$method($args);
				$ver = true;
			}
		}
		// if($static === false){
		// 	$a = new $class();
		// 	return $a->$method(...$args);
		// }
		// else
		// 	return $class::$method(...$args);
		if($verif == true)
			$res = ['success' => $ver, 'result' => $res];
		return $res;
	}

	public function alternate(){
		return false;
	}
	protected function getFactorySpace(){
		die('Error give the Factory namespace !');
	}
	protected function initAlternate($class, $args){
		$path = is_array($this->alternate()) ? $this->alternate() : (is_string($this->alternate()) ? [$this->alternate()] : null);
		if(!is_null($path)){
			if($a = Dir::scan($this->alternate(), 'php', null, true, null, null, true)){
				$method = $class;
				foreach ($a as $key => $value) {
					$cl = $this->getFactorySpace() . $value['filename'];
					$rs = $this->calledMethod($cl, $method, $args, true, false, $value['filename']);
					if($rs !== [['dwuh82e27t&???&?W!T?&@T@&@*&#@&#F@&?E@UQY']])
						return $rs;
				}
			}
		}
		die(__CLASS__ . ' : Method(' . $method . ') or Class(' . $class . ') : ' . __FILE__ . ' 101');
		// return false;
	}
}