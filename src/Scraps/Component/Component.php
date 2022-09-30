<?php 
namespace Scraps\Component;
use Scraps\Factory\Factory;
use Scraps\Helper\Helper;
use Support\Dir;
use View;
/**
 * 
 */
class Component extends Factory{

	private static $components = [];
	private static $assetsComponents = [];
	private static $allComponentsFiles = [];

	public static function init() : void {
		if(config('component')['generate'] === true){
			$clocation = str_replace('/', '\\', config('component')['location']);
			$namespace = config('component')['namespace'];
			$dd = Dir::scandir($clocation, true);
			$dd = $dd === false ? false : $dd->scan(null, 'php', null, true);
			if($dd !== false){
				foreach ($dd as $key => $value) {
					$f = pathinfo(str_replace($clocation, '', $value));
					$a = explode('\\', $f['dirname']);
					$f = $f['filename'];
					$nspc = $alias = '';
					foreach ($a as $v) {
						$nspc .= empty($v) ? '' : "\\" . ucfirst($v);
						$alias .= ucfirst($v);
					}
					$class = str_replace('\\\\', '\\', $namespace . $nspc . '\\' . ucfirst($f));
					$alias .= ucfirst($f);
					self::$components[$alias] = $class;
				}
			}
		}
	}

	public function module(array|string $alias, string $class) : void {
		$alias = is_array($alias) ? $alias : [$alias];
		foreach ($alias as $value) {
			self::$components[$value] = $class;
		}
	}
	public function getModule(string|null $alias = null) : array|string {
		return is_null($alias) ? self::$components : self::$components[$alias];
	}
	protected function getFactorySpace(){return 'Scraps\Component\Factories\\';}

	public function componentRender(string $component, array $datas = [], bool $ret = false, bool $classVer = false, bool $except = true){
		if($this->getModule($component)){
			$c = $this->getModule($component);
			$class = new $c(...$datas);
			$class->render();
			if(!isset(self::$assetsComponents[$c])){
				self::$assetsComponents[$c] = $class->getConfig()['assets'];
				foreach (self::$assetsComponents[$c] as $k => $value) {
					self::$assetsComponents[$c][$k]['path'] = is_array(self::$assetsComponents[$c][$k]['path']) ? self::$assetsComponents[$c][$k]['path'] : [self::$assetsComponents[$c][$k]['path']];
					foreach (self::$assetsComponents[$c][$k]['path'] as $key => $v) {
						if(!in_array($key, self::$assetsComponents[$c][$k]['exceptions']) || $except === false){
							self::$assetsComponents[$c][$k]['url'][] = preg_match('#http://(.*?)|https://(.*?)#', $v) ? $v : assets(switchPoint($v) . '.' . (isset(self::$assetsComponents[$c][$k]['extension']) ? self::$assetsComponents[$c][$k]['extension'] : $k));
						}
					}
				}
			}
			$render = $this->viewRender(...$class->getComponentRender());
			if($ret === false && $classVer === false) {
				echo $render;
				return $render && !empty(trim($render)) ? true : false;
			}
			else{
				if($classVer === true) return ['class' => $class, 'render' => $render];
				else return $render;
			}
		}
		return false;
	}
	public static function assetRender(string|null $t = null, bool $files = false, bool $ret = false){
		$tag = [];
		if($t == null || $t == 'css')
			$tag['css'] = '<link rel="stylesheet" type="text/css" href="--url--">';
		if($t == null || $t == 'js')
			$tag['js'] = '<script type="text/javascript" src="--url--"></script>';
		$res = '';
		foreach (self::$assetsComponents as $c => $value) {
			if(method_exists($c, 'tag') && $files === false){
				$m = 'tag';
				$res .= $c::$m($value);
			}
			else{
				foreach ($value as $k => $val) {
					if(isset($tag[$k]) || $files === false){
						foreach ($val['url'] as $v){
							self::$allComponentsFiles[] = $v;
							$res .= str_replace('--url--', $v, $tag[$k]) . "\n";
						}
					}
				}
			}
		}
		if($ret === false && $files === false) echo $res;
		else return $files === false ? $res : self::$allComponentsFiles;
	}
	public static function getAssetsComponents()
	{
		return self::$assetsComponents;
	}
	public static function getAllComponentsFiles(){
		return self::$allComponentsFiles;
	}
	public static function getComponents(){
		return self::$components;
	}
	public function viewRender(string $path, array $datas = []){
		$path = !file_exists($path) ? View::s_getRessourcesViewPath() . '/' . str_replace('.', '/', trim($path, '.')) . '.php' : $path;
		if(!file_exists($path))
			die('This file not exits : "' . $path . '".');
		else{
			if(!function_exists('scraps\component\rend90u2e92ue9202e')){
				function rend90u2e92ue9202e($datasnuid3u3u38f3u8u3377t, $loc89e3h38938yhe38eh) : string {
					ob_start();
					extract($datasnuid3u3u38f3u8u3377t);
					$a = require $loc89e3h38938yhe38eh;
					// $layout8u383ge83g3822 = Render::getRender('layout');
					// if($layout8u383ge83g3822) require $layout8u383ge83g3822;
					return ob_get_clean();
				}
			}
			return rend90u2e92ue9202e($datas, $path);
		}
	}
	public static function folder(array|string $location){
		$location = is_string($location) ? [$location] : $location; $r = false;
		error_reporting(E_ALL & ~E_WARNING);
		foreach ($location as $k => $path) {
			if(!file_exists($path) && !is_file($path)){
				$r = mkdir($path, 0777, true);
			}
		}
		error_reporting(E_ALL);
		return $r;
	}
	public static function classGen(int $chr = 8) : string{
		$t = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
		$res = '';
		for ($i = 0; $i < $chr; $i++) {
			$res .= $t[random_int(0, (count($t) - 1))];
		}
		return $res;
	}
}
	