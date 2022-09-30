<?php
namespace Scraps\Lang;
/**
 * 
 */
class Lang{

	protected static $lang;

	public static $path = ROOT . '\ressources\lang\\';

	public function __construct(){

		$this->init();


	}

	public function init(){

		$lang = null;

		if(isset($_GET['lang']) && !empty(trim($_GET['lang'])))
			$lang = trim($_GET['lang']);

		if(is_null($lang) && config('autolang')){

			$lang = explode(',', (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : ''))[0];

		}
		if(!is_null($lang) && self::exists($lang)){ self::set($lang); return true; }

		self::set(config('fallback_locale'));

		return true;

	}

	public static function set($lang){ self::$lang = $lang; return true; }

	public static function get(){ return self::$lang; }

	public static function exists($lang){

		return file_exists(self::$path . $lang);

	}

	public static function getPath(){

		return self::$path;

	}

}