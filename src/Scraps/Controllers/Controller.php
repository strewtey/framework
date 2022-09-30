<?php 
namespace Scraps\Controllers;
use Scraps\Factory\Factory;
/**
 * 
 */
class Controller extends Factory{

	protected function getFactorySpace(){ return 'Scraps\Controllers\Factories\\'; }
	
	public function __construct(){
		// code...
	}
}