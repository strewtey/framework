<?php
namespace Scraps\Helper;
/**
 * 
 */
class Helper{
	protected static $ins;
	protected $usersHelpers;

	protected static $loaded = [];
	public static function singleton(){
		$c = __CLASS__;
		return is_null(static::$ins) ? new $c() : static::$ins;
	}
	public function __construct(){
		if(file_exists(ROOT . '/app/helpers.php')){
			$this->usersHelpers = require ROOT . '/app/helpers.php';
		}
	}
	public static function load(array|string $file){
		$file = is_array($file) ? $file : [$file];
		foreach ($file as $key => $value) {
			if(!in_array($file, self::$loaded)){
				if(is_string($value) && file_exists($value)){
					return require $value;
					self::$loaded[] = $file;
				}
				else
					die('Verify your helpers file !');
			}
		}
	}
}