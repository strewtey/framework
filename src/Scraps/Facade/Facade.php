<?php 
/**
 * 
 */
class Facade{

	protected static $instances;
	protected static $app;

	protected static function getRoot(){
		return static::instance(static::getAccessor());
	}
	protected static function instance($class){

		if(is_object($class)) return $class;

		if(isset(static::$instances[$class]) && static::singleton()) return static::$instances[$class];

		if(class_exists($class) && static::singleton()) {

			return static::$instances[$class] = new $class();

		}

		elseif(class_exists($class) && static::singleton() === false)
			return new $class();
	}
    protected static function getFacadeAccessor(){
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

	public static function __callStatic($method, $args){

		if($a = static::method($method, $args)) return $a;

		$instance = static::getRoot();

		if(!$instance) throw new RuntimeException('A facade root has not been set.');
		// Verify Methods
			// if(method_exists(static::getAccessor(), $method)) return $instance->$method(...$args);
		// Verify Property
			// if(property_exists(static::getAccessor(), $method)) return $instance->$method;
		return $instance->$method(...$args);
	}

	protected static function clearResolvedInstances() {
		static::$instances = [];
	}
	protected static function singleton(){
		return true;
	}
	protected static function method($method, $args){
		$result = false;
		$method = static::getAccessor() . '::' . $method;
		if(static::getAccessor() == 'Scraps\Cookie\Cookie'){
			if(is_callable($method))
				return $method(...$args);
		}
		return false;
	}
}