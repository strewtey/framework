<?php 
namespace Scraps\Cookie;
/**
 * 
 */
class Cookie {

	protected static $allCookies = 19;
	protected static $alternative = ['--__$$|-false-|$$__--', '--__$$|-true-|$$__--'];
	public static $indexes = ['name', 'value', 'expires', 'path', 'domain', 'secure', 'httponly', 'timestamp'];
	protected $defaultTime = 'mo';
	
	public function __construct(){
		self::init();
		// if(is_null(Cookie::$allCookie))
	}
	public static function exists($name){
		return is_callable('Scraps\Cookie\Cookie::exists');
	}
	public function create(string $name, string $value = "", array|int $expires = [1, 'y'], string $path = "", string $domain = "", bool $secure = false, bool $httponly = false){
		if(!isset($_COOKIE[$name])){
			if(empty($expires) && !is_int($expires[0]) && $expires[0] >= 0) die('Date d\'expiration invalide !');
			$tm = $this->time($expires);
			$tab = [
				self::$indexes[0] => $name,
				self::$indexes[1] => $value,
				self::$indexes[2] => $tm['expires'],
				self::$indexes[3] => $path,
				self::$indexes[4] => ($domain === true ? self::$alternative[0] : self::$alternative[1]),
				self::$indexes[5] => ($secure === true ? self::$alternative[0] : self::$alternative[1]),
				self::$indexes[6] => ($httponly === true ? self::$alternative[0] : self::$alternative[1]),
				self::$indexes[7] => $tm['timestamp']
			];
			$a = crypty(implode('*?_#*@?!!', $tab));
			setcookie($name, $a, $tm['timestamp'], $path, $domain, $secure, $httponly);
			return true;
		}
		return false;
		// $b = ;
		// $r = ['aa', 'bb', 'cc'];
		// var_dump($this->qww(...$r));
		// $this->hour = $hour;
		// self::$allCookies[$name] = $a;
		// var_dump($tab);

	}
	public function time(array|int $time = [0]){
		$time = is_int($time) ? [$time, $this->defaultTime] : $time;
		$s = $time[0] > 1 ? 's' : '';
		$a = ['y', 'mo', 'w', 'd', 'h', 'mi', 'm'];
		$time = isset($time[1]) ? $time : [$time[0], $this->defaultTime];
		$time = in_array($time[1], $a) ? $time : [$time[0], $this->defaultTime];
		$time = $time[0] == 0 ? [1, $time[1]] : $time;
		$mult = 0;
		switch ($time[1]) {
			case 'y': $mult = 31557600 * $time[0]; break;
			case 'mo': $mult = 2592000 * $time[0]; break;
			case 'w': $mult = 604800 * $time[0]; break;
			case 'd': $mult = 86400 * $time[0]; break;
			case 'h': $mult = 3600 * $time[0]; break;
			default: /* mi */ $mult = 60 * $time[0]; break;
		}
		$texp = time() + $mult;
		$result = ['timestamp' => $texp, 'expires' => date('C\e C\o\ok\i\e \e\x\p\i\r\e (\D\D/\M\M/\Y\Y) \L\e d/m/Y (\H\H/\M\M/\S\S) à H:i:s', $texp)];
		return $result;
	}
	public function qww($a, $b, $v)
	{
		return $a . $b . $v;
	}
	public static function getAllCookies($key = null){
		self::init();
		$res = !is_null($key) && isset(self::$allCookies[$key])
			? self::convert(self::$allCookies[$key])
			: (is_null($key) ? self::convert(self::$allCookies) : false)
		;
		return $res;
	}
	public static function convert(string|array $vall){
		$value = is_array($vall) ? $vall : [$vall];
		$res = [];
		foreach ($value as $key => $val) {
			$r = uncrypty($val);
			$r = explode('*?_#*@?!!', $r);
			$aa = [];
			foreach ($r as $k => $v) {
				$k = self::$indexes[$k];
				if($v == self::$alternative[1])
					$aa[$k] = false;
				elseif ($v == self::$alternative[0])
					$aa[$k] = true;
				else
					$aa[$k] = $v;
			}
			$res[] = $aa;
		}
		return is_array($vall) ? $res : array_shift($res);
		// code...
	}
	public static function init(){
		if(self::$allCookies != $_COOKIE){
			self::$allCookies = $_COOKIE;
			return true;
		}
		return false;
	}
	public function has($name){
		return $this->getAllCookies($name) ? true : false;
	}
}



/*

*/