<?php
namespace Scraps\Error\Entity;
use Scraps\Error\Entity\Traits\Console\Win;
/**
 * 
 */
class Console {
	use Win;

	public static function init(string $message){
		return '';
	}
	public static function warning(){

	}
	public static function fatal(){

	}
	public static function getInterface(){}
}