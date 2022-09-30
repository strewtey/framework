<?php 
namespace Scraps\Config;
use Support\Dir;
// use Scraps\Factory\Factory;
/**
 * 
 */
class Config /*extends Factory*/{
	private static $allDatasConfig = [];
	public function __construct($path = ROOT . '\config'){
		if(file_exists($path) && (is_null(self::$allDatasConfig) || empty(self::$allDatasConfig))){
			$f = Dir::scan($path, 'php', null, null, null, null, true);
			foreach ($f as $key => $value) {
				self::$allDatasConfig[$value['filename']] = require $value['dirname'] . '\\' . $value['basename'];
			}
		}
		elseif(is_null(self::$allDatasConfig) || empty(self::$allDatasConfig))
			die('"config" folder doesn\'t exists !');
	}
	public function get(string|null $key, string|null $default = null){
		$default = is_null($default) ? 'app' : $default;
		return $default === true 
			? self::$allDatasConfig
			: (
				isset(self::$allDatasConfig[$default][$key]) 
					? self::$allDatasConfig[$default][$key]
					: (
						isset(self::$allDatasConfig[$default]) && $key == null
							? self::$allDatasConfig[$default] 
							: null
					)
			);
	}
	public function set(string|array $key, string|null $default = null){
		if(!is_array($key) && is_null($value)) return false;
		$default = is_null($default) ? 'app' : $default;
		$res = false;
		$data = is_array($key) ? $key : [$key => $value];
		foreach ($data as $k => $val) if(isset(self::$allDatasConfig[$default][$k])) self::$allDatasConfig[$default][$k] = $val;
		return $res;
	}
	public function der(){
		return "string";
	}
}