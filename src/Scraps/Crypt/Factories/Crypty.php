<?php
namespace Scraps\Crypt\Factories;
/**
 * La Classe Crypty sert à encrypter et à decrypter une chaîne de caractère
 * en utilisant différents fonctions de cryptage et d'inversion de caractère
 * en plaçant des séparateur en ortographique pour ne pas avoir une chaine
 * encrypté Unicode
 * 
 * (c) Gedeon Timothy <gedeonbateko3@gmail.com>
 * (wr) Merdie Elongo <merdielongo9@gmail.com>
 * 
 */
class Crypty{
	// private $result;
	private $separator = [
		'sep' => [
			['i$=j??.e', ['i', '$', '=', 'j', '?', '?', '.', 'e']],
			['os!!75!0', ['o', 's', '!', '!', '7', '5', '!', '0']],
			['%()j1@kq', ['%', '(', ')', 'j', '1', '@', 'k', 'q']],
			['_;fm,*22', ['_', ';', 'f', 'm', ',', '*', '2', '2']]
		],
		'count' => 4
	];
	private $extend = 'oshnwzcjeulbtqipmgxavrydfk';
	protected $str;

	public function __construct(string|null $value = null, bool $crypt = true){
		$this->str = $value;
		// if(!is_null($value)){
		// 	if($crypt === true) $this->crypt($value);
		// 	else $this->decrypt($value);
		// }
	}
	public function crypt(){
		$base = base64_encode($this->str);$uu = convert_uuencode($base);
		$r = strrev($base) . $this->separator['sep'][random_int(0, $this->separator['count'] - 1)][0] . strrev($uu);
		$base = convert_uuencode($r);
		return $base;
	}
	public function decrypt(){
		error_reporting(E_ALL & ~E_WARNING);
		$a = convert_uudecode($this->str);
		error_reporting(E_ALL);
		if($a){
			$index = null;
			foreach ($this->separator['sep'] as $key => $value) {
				$t = str_replace($value[0], '', $a);
				if($a != $t){
					$index = $key;
					break;
				}
			}
			// return [$t, 'key = ' . ($index + 1), htmlspecialchars($a)];
			if(!is_null($index)){
				// return [$this->separator['sep'][$index], htmlspecialchars($a)];
				$p = explode($this->separator['sep'][$index][0], $a);
				return base64_decode(strrev($p[0]));
			}
		}
		return false;

	}
	public static function ff($value=''){
		// code...
	}
	public function result(){
		return $this->result;
	}
	public function filter(int $len, bool $arr = false){
		$result = [];
		$result0 = '';
		for ($i = 0; $i < $len; $i++) {
			$ind = random_int(0, (strlen($this->extend) - 1));
			$rep = $this->extend[$ind];
			if($arr === false) $result0 .= $rep;
			else $result[] = $rep;
		}
		return !empty($result) ? $result : $result0;
	}
}
