<?php 
namespace Scraps\Controllers\Factories;
/**
 * 
 */
class ComponentController{
	
	public function __construct($component, $datas = []){
		header('Content-Type: text/json');

		// var_dump($component, $datas);
		// echo "<pre>";
		// var_dump(json_decode($datas, true));
		// echo "</pre>";
		$component = component($component, json_decode($datas, true), false, true);
		$class = array_shift($component);
		// var_dump();
		foreach ($class->getConfig('assets') as $key => $value) {
			// $componentClass['files'][] = ['ext' => $key, 'url' => assets(switchPoint($value['path']) . '')]
			$ext = isset($value['extension']) ? $value['extension'] : $key;
			foreach ($value['path'] as $k => $v)
				$component['files'][] = [
					'url' => assets(switchPoint($v) . '.' . $ext),
					'extension' => $ext
				];
		}
		$jsResponse = str_replace('\r', '', str_replace('\t', '', str_replace('\n', '', json_encode($component))));
		header('Content-Length: '. strlen($jsResponse));
		echo $jsResponse;
	}
}