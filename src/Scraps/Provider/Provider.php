<?php 
namespace Scraps\Provider;
use Scraps\Factory\Factory;
use Support\Dir;
/**
 * 
 */
abstract class Provider extends Factory{

	public static $appProviderClasses = [];
	

	public static function init(){
		$apr = self::getAppProviderClasses();
		$method = 'boot';
		foreach ($apr as $class) {
			if(method_exists($class, $method)){
				$a = new $class();
				$a->$method();
			}
		}
	}
	protected function getFactorySpace(){return 'Scraps\Provider\Factories\\';}

	protected static function getAppProviderSpace(){return 'App\Providers\\';}
	protected static function getAppProviderPath(){return ROOT . '\\' . 'app\Providers';}
	protected static function getAppProviderClasses(){
		if(empty(self::$appProviderClasses)){
			foreach (Dir::scan(self::getAppProviderPath(), 'php', null, null, null, null, true) as $key => $value) {
				$c = self::getAppProviderSpace() . $value['filename'];
				self::$appProviderClasses[] = $c;
			};
			return self::$appProviderClasses;
		}
		else return self::$appProviderClasses;
	}
}