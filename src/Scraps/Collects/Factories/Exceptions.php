<?php
namespace Scraps\Collects\Factories;

/**
 * 
 */
final class Exceptions {
	public $general_exceptions = [];

	public function __construct($config = null){
		if($config) $this->general_exceptions = isset($config['#options']) && isset($config['#options']['exceptions']) ? $config['#options']['exceptions'] : $this->getGeneralException();
	}

	public function getGeneralException(){
		return $this->general_exceptions; 
	}
	public function setGeneralException(array|string $exceptions){
		$this->general_exceptions = $exceptions; 
		return $this;
	}
	public function exceptionMerge(array $value){
		return array_merge($value, $this->getGeneralException());
	}
}
