<?php
namespace Scraps\Consoles;
/**
 * 
 */
class Console {

	private $commandLines = [];
	
	public function __construct(){}

	public function readline(string|null $key = null) : string {
		$key = is_null($key) ? null : ((string) md5($key));
		$r = readline();
		if($key !== null) $this->commandLines[$key] = $r;
		else $this->commandLines[] = $r;
		return $r;
	}
	// public function (){
	// 	// code...
	// }
}