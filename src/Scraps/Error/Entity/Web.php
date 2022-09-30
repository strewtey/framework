<?php
namespace Scraps\Error\Entity;
// use Error as ErrorBase;
use Scraps\Error\Error;
// use Scraps\Error\Interfaces\ErrorEntity;
use Scraps\Error\Entity\Traits\Web\Html;
use Scraps\Error\Entity\Traits\Web\Json;
use Scraps\Error\Entity\Traits\MessageConvert;
/**
 * 
 */
class Web {
	use Html;
	use Json;
	use MessageConvert{
		messageConvert as convert;
	}

	protected static $message;
	protected static $method;
	protected static $style;
	protected static $accept;

	
		// var_dump(self::___INTERFACES);
	public static function init(string $message, $style = 1){
		self::$message = self::convert($message, Error::getTrace(), "<br>");
		self::$style = $style;
		self::$method = Error::getErrorType();
		$m = self::$method;
		// header(string)
		// header('Content-type: text/html');
		$mm = ucfirst(str_replace('text/', '', explode(',', $_SERVER['HTTP_ACCEPT'])[0]));
		$m = $m . $mm;
		if(method_exists(__CLASS__, $m)){
			echo(self::$m());
		}
		else{Text::init($message, $style);}
	}
}