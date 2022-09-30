<?php
/**
 * 
 */
class Cookie extends Facade{
	
    protected static function getAccessor(){
        return 'Scraps\Cookie\Cookie';
    }
    // protected static function instance($class){
    //     return static::$instances[$class] = new $class();
    // }

    protected static function singleton(){return false;}
}