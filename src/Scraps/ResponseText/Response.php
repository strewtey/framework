<?php 
namespace Scraps\ResponseText;
use Scraps\Lang\Lang;
/**
 * 
 */
class Response {
	
	public static function res(string|callable $str, string $lang = null){
		// echo Lang::getPath();
		$arr = explode(':', $str);
		$l = lang();
		if(count($arr) == 2){
			if(!is_null($lang)){
				if (Lang::exists($lang)) $l = $lang;
				else die('This language "' . $lang . '" doesn\'t exists !');
			}
			$key = end($arr);
			$path = str_replace('.', '\\', $arr[0]);
			$path = str_replace('/', '\\', $path);
			$fl = Lang::getPath() . $l . '\\' . $path . '.php';
			if(file_exists($fl) && $a = require $fl){
				if(isset($a[$key]))
					return $a[$key];
				else die('This Response Key "' . $key . '" in Language "' . $l . '" doesn\'t exists !');
			}
			// $str = explode('.', $str);
			// $f = array_shift($str);
		}
		else die('Error ! Use ":" one times not this "' . $str . '" (Ex. path.file:key)');
	}
}