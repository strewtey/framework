<?php
namespace Scraps\Error\Entity;
// use Error as ErrorBase;
use Scraps\Error\Error;
use Scraps\Error\Interfaces\ErrorEntity;
use Scraps\Error\Entity\Traits\MessageConvert;
/**
 * 
 */
// Error::getTrace()
// Error::getAllError()
class Text implements ErrorEntity
{
	use MessageConvert{
		messageConvert as convert;
	}
	protected static $message;
	protected static $method;
	protected static $style;

	// Error::getErrorType()

	public static function init(string $message, $style = 1){
		self::$message = self::convert($message, Error::getTrace(), "\n");
		self::$style = $style;
		self::$method = Error::getErrorType();
		$m = self::$method;
		echo(self::$m());
	}
	public static function warning() : string{
	}
	public static function fatal() : string{
		switch (self::$style) {
			case 2:
				echo "string";
				break;
			
			default:
				return self::$message;
				break;
		}
	}
}