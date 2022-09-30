<?php
/**
 * 
 */
class DB extends Facade{
	
    protected static function getAccessor(){

        return 'Scraps\Database\Database';

    }

    public static function init(){

        return static::instance(static::getAccessor());

    }

}