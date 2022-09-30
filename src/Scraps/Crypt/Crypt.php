<?php 
namespace Scraps\Crypt;
use Scraps\Factory\Factory;
/**
 * 
 */
class Crypt extends Factory
{

	protected function getFactorySpace(){return 'Scraps\Crypt\Factories\\';}

	// public function alternate(){

	// 	return __DIR__ . '\Factories';

	// }

	// public function __call($class, $args){

	// 	$c = $this->getFactorySpace() . ucfirst(strtolower($class));

	// 	if(!empty($this->alternate())) return $this->initAlternate($class, $args);

	// 	if(class_exists($c)) return $this->called($c, $args);

	// 	die("This method \"" . $class . "\" not exists !");

	// }
	
}