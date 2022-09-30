<?php 
namespace Scraps\Controllers\Traits;
/**
 * 
 */
trait StaticCall{
	public static function __callStatic($class, $params){
		$c = \Scraps\Controllers\Controller::class;
		$c = new $c();
		return $c->$class(...$params);
	}
	public static function staticCallable(){
		return true;
	}
}