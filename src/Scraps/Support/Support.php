<?php 
namespace Support;
/**
 * 
 */
class Support{
	protected $combine;
	protected static $aliases;
	public function __construct(){
		if(is_null(static::$instance))
			static::$instance = $this;
	}
	public static function __callStatic($method, $args){
		$a = static::singleton();
		$m = '';
		if($rep = static::getAlias($method))
			$m = $rep;
		else
			$m = '_' . $method;
		if(method_exists(get_called_class(), $m)){
			return $a->$m(...$args);
		}
		else
			die('Method not exists ! "' . $method . '"');
	}
	public function __call($method, $args){
		if($rep = static::getAlias($method))
			$m = $rep;
		else
			$m = '_' . $method;
		if(method_exists(get_called_class(), $m))
			return $this->$m(...$args);
		else
			die('Method not exists ! "' . $method . '"');
	}
	public static function singleton(){
		if(is_null(static::$instance)){
			$c = get_called_class();
			static::$instance = new $c();
		}
		return static::$instance;
	}
	protected function initCombine($combine, $oth = false){
		return $combine::setCombine($this->combine, $oth);
	}
	public function _setCombine($combine, $oth = false){
		$this->combine = $combine;
		// var_dump($this->combine);
		if($oth == true)
			return $this;
	}
	protected function cResult(){
		echo '<pre style="background:black;color:white;padding:15px;font-size:14px;font-family:Consolas;line-height:30px;">';
		var_dump($this->combine['result']);
		echo '</pre>';
	}
	public static function getAlias($method){
		return isset(static::$aliases[$method]) ? static::$aliases[$method] : false;
	}
}