<?php 
namespace Core\View;
use Core\Factory\Factory;
use Support\Dir;
use Core\Helper\Helper;
/**
 * 
 */
class View extends Factory{
	public $ressourcesViewPath = ROOT . '/ressources/views';
	public static $s_ressourcesViewPath = ROOT . '/ressources/views';
	public static $helper = false;
	// public $datas;
	// public static $render;
	
	public function __construct(){
		if(!file_exists($this->ressourcesViewPath))
			die('This path not exits : "' . $this->getRessourcesViewPath() . '". Create it !');
	}
	protected function getFactorySpace(){return 'Core\View\Factories\\';}
	public function getRessourcesViewPath(){
		return $this->ressourcesViewPath;
	}
	public static function s_getRessourcesViewPath(){
		return self::$s_ressourcesViewPath;
	}



	public function __call($class, $args){
		$c = $this->getFactorySpace() . ucfirst(strtolower($class));
		if(class_exists($c)){
			$args = array_merge([$this], $args);
			return $this->called($c, $args);
		}
		else{
			$method = $class;
			foreach (Dir::scan(__DIR__ . '/Factories', 'php', null, null, null, null, true) as $key => $value) {
				// $c = $this->getFactorySpace() . $value['filename'];
				$c = $this->getFactorySpace() . $value['filename'];
				// var_dump($c);exit();
				$r = $this->calledMethod($c, $method, $args, true, true);
				if($r['success']){
					return $r['result'];
				}
			}
			die("This method \"" . $c . "\" not exists ! " . __FILE__);
		}
		// $method .= 's';
		// return $this->$method(...$args);
	}
}














/*

			$st = "Mon code {{ first code }} {{ seconde conde }} {{-- comment --}} vraies";
			echo "<pre>";
			print_r($st.'<br><br>');

			# preg_match_all('/({{--.*?--}})/', $st, $comment);
			$st = preg_replace('/{{--(.*?)--}}/', '/* $1 *<sup-sup>/', $st);
			print_r($st.'<br><br>');

			# preg_match_all('/({{.*?}})/', $st, $code);
			$st = preg_replace('/{{(.*?)}}/', 'eval($1)', $st);
			print_r($st.'<br><br>');

			echo "</pre>";
*/