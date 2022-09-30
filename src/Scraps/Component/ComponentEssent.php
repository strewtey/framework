<?php
namespace Scraps\Component;
/**
 * 
 */
abstract class ComponentEssent{
	private $componentRenderE;
	protected function componentRender(string $path, array $datas = []){
		$this->componentRenderE = ['path' => $path, 'datas' => $datas];
	}
	public function getComponentRender(){
		return $this->componentRenderE;
	}
}