<?php
namespace Scraps\Singleton;
/**
 * 
 */
class Singleton {

	protected static $instance;

	public static function getInstance(){

		$c = static::class;

		return is_null(self::$instance) ? new $c() : self::$instance;

	}

}