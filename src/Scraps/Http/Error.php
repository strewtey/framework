<?php 
namespace Scraps\Http;
use Scraps\Route\Route;
/**
 * 
 */
class Error {
	public static $errorCode;
	public $code;
	public static function init(int|null $code = null){
		self::$errorCode = is_null($code) ? http_response_code() : $code;
		preg_match("/([0-9]).*/", self::$errorCode, $code);
		if($code[1] != 2){
			$c = __CLASS__;
			return new $c(self::$errorCode, true);
		}
	}
	public function __construct(int|null $code = null, bool $init = false){
		if($init == true){
			$this->code = $code;
			if(method_exists(__CLASS__, 'e' . $this->code)){
				$m = 'e' . $this->code;
				$this->$m();
			}
		}
	}
	public function e404($message = null){
		$hMessage = is_null($message) ? 'Not Found !' : $message;
		header('HTTP/1.0 404 ' . trim($message));
		if(Route::$fallback)
			call_user_func(Route::$fallback);
		else
			if(isset(Route::$error[404])) call_user_func(Route::$error[404]);
			else view('error.404');
	}
}