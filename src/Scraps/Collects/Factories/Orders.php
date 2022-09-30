<?php
namespace Scraps\Collects\Factories;

/**
 * 
 */
final class Orders {
	public $general_orders = [];

	public function __construct($config = null){
		// var_dump($config);exit('ss');
		if($config) $this->general_orders = isset($config['#options']) && isset($config['#options']['orders']) ? (is_array($config['#options']['orders']) ? $config['#options']['orders'] : (is_string($config['#options']['orders']) ? [$config['#options']['orders']] : $this->getGeneralOrder())) : $this->getGeneralOrder();
	}
	public function getGeneralOrder(string|null $key = null){
		return !empty($key) ? (isset($this->general_orders[$key]) ? $this->general_orders[$key] : []) : $this->general_orders; 
	}
	public function setGeneralOrder(array|string $orders, string|null $key = null){
		$orders = is_string($orders) ? [$orders] : $orders;
		if($key !== null && isset($this->collects[$key]))
			$this->collects[$key]['orders'] = $orders;
		else
			$this->general_orders = $orders; 
		return $this;
	}
	public function OrderMerge(array $value){
		return array_merge($value, $this->getGeneralOrder());
	}
}
