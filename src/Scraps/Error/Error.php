<?php
namespace Scraps\Error;
use Scraps\Error\Interfaces\ErrorEntity as I_ErrorEntity;
use Error as ErrorBase;

// use Scraps\Error\Interfaces\ErrorType;  implements ErrorType
/**
 * 
 */
abstract class Error{
	private static $interfaces = I_ErrorEntity::___INTERFACES;
	private static $errorTypeList = I_ErrorEntity::___ERROR_TYPE_LIST;
	private static $errorType;
	private static $allError;
	private static $trace;
	private static $remote = 2;
	public static function __callStatic($errorType, $params = []){
		self::initError();
		$cl = '';
		if(!$cl = self::entityVerif($errorType))
			$cl = self::getSpaceEntity() . 'Text';
			// self::setErrorType($errorType); // a enlever apres avoir tester la class Text
		$cl::init(...$params);
		exit;
	}

	private static function initError(){
		try { throw new ErrorBase("Un message d'erreur"); } catch(ErrorBase $e) { $al = $e; }
		self::setAllError($e);
		$a = $e->getTrace()[2];
		if(isset($e->getTrace()[self::$remote]))
			$a = $e->getTrace()[self::$remote];
		self::setTrace($a);
	}
	public static function setRemote(int $value) : void{
		self::$remote = $value;
	}
	public static function getAllError(){ return self::$allError; }
	private static function setAllError($val){ self::$allError = $val;}
	public static function getTrace(){ return self::$trace; }
	private static function setTrace(array $val){ self::$trace = $val;}

	private static function sapi() : string { return php_sapi_name(); }
	public static function getInterface(){
		$response = false;
		foreach (self::$interfaces as $key => $value) {
			if(in_array(self::sapi(), $value)){
				$response = $key;
				break;
			}
		}
		return $response;
	}
	// Scraps\Error\Error::getErrorType()
	public static function getErrorType() : string|null|bool { return self::$errorType; }
	private static function setErrorType(string $errorType) : bool{
		if(in_array($errorType, self::$errorTypeList)){
			self::$errorType = $errorType;
			return true;
		}
		return false;
		
	}
	private static function getSpaceEntity() : string { return 'Scraps\Error\Entity\\'; }
	private static function entityClass(string $class) : string { return self::getSpaceEntity() . ucfirst($class); }
	private static function entityVerif(string $errorType) : string { return $err = self::setErrorType($errorType) && class_exists(self::entityClass(self::getInterface())) ? self::entityClass(self::getInterface()) : false; }
}
// private static function init(string $message, array $style = []){ }