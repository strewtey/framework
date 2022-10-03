<?php 
namespace Scraps\View\Factories;

/**
 * 
 */
class Render{
	// public $ressourcesViewPath = ROOT . '/ressources/views';
	// public static $s_ressourcesViewPath = ROOT . '/ressources/views';
	protected $datas;
	protected $view;
	protected static $render;
	
	public function __construct(\Scraps\View\View|null $view = null, string|null $path = null, array|object|null $datas = [], array|string|null $layout = null){
		if(!is_null($view)){
			$this->datas = $datas;
			$this->view = $view;
			$this->render($path, $this->datas, $layout);
		}
	}
	public static function setRender(string|array $key, $value = null, string $keyP = null, $push = false){
		$key = is_array($key) ? $key : [$key => $value];
		if($value === true){
			self::$render = $key;
			return true;
		}
		foreach ($key as $k => $val) {
			if(isset(self::$render[$k])){
				if(!is_array(self::$render[$k])) self::$render[$k] = [];
				if(is_null($keyP)){
					array_push(self::$render[$k], $val);
				}
				else{
					// if(is_array($value))
					// 	self::$render[$k][$keyP] = array_shift($value);
					// else
					if($push === true && isset(self::$render[$k][$keyP])){
						$a = self::$render[$k][$keyP];
						is_array(self::$render[$k][$keyP]) ? array_push($a, $value) : ($a = [self::$render[$k][$keyP], $value]);
						self::$render[$k][$keyP] = $a;
					}
					else self::$render[$k][$keyP] = $value;
				}
				// Mise a jour bizarre
				// is_null($keyP) ? array_push(self::$render[$k], $val) : (self::$render[$k][$keyP] = (is_array($value) ? array_shift($value) : $value));
				
			}
			else
				// Mise a jour bizarre
				self::$render[$k] = is_null($keyP) ? $val : [$keyP => $val];
		}
		return true;
	}
	public static function getRender($key = null){
		return is_null($key) ? self::$render : (isset(self::$render[$key]) ? self::$render[$key] : false);
	}
	public function render(string $path, array|object $datas = [], array|string|null $layout = null){
		$this->datas = $datas;
		$layout = is_null($layout) ? null : (
			is_array($layout) ? (isset($layout['file']) && is_string($layout['file']) && !empty($layout['file']) ? $layout : null) :
			(is_string($layout) ? ['file' => $layout] : null)
		);
		if($layout != null) $layout['file'] = $this->view()->getRessourcesViewPath() . '/layouts/' . switchPoint($layout['file']) . '.php';
		$path = !file_exists($path) ? $this->view()->getRessourcesViewPath() . '/' . str_replace('.', '/', trim($path, '.')) . '.php' : $path;
		if(!file_exists($path))
			die('This file not exits : "' . $path . '".');
		else{
			if(!function_exists('scraps\view\factories\rend273872632562')){
				function rend273872632562($datas019287321, $loc297329521, $layout_____8y282g84g2 = null) : string {
					ob_start();
					extract($datas019287321);
					$a = require $loc297329521;
					$layout218219361312 = $layout_____8y282g84g2 == null ? Render::getRender('layout') : $layout_____8y282g84g2;
					if($layout218219361312 && isset($layout218219361312['file']) && file_exists($layout218219361312['file'])){
						if(!empty($layout218219361312['datas']))
							extract($layout218219361312['datas']);
						require $layout218219361312['file'];
					}
					return ob_get_clean();
				}
			}
			echo rend273872632562($this->datas, $path, $layout);
		}
	}
	public function view(){
		return is_null($this->view) ? die('Your View model is not valide !') : $this->view;
	}
}