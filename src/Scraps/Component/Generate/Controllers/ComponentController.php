<?php 
namespace Scraps\Component\Generate\Controllers;
use Scraps\Component\Component;
/**
 * 
 */
class ComponentController /*extends Controller*/{

	public function __construct(){ /*...*/ }

	public function index($name, $more = null){
		$options = [
			'generate' => ['class', 'css', 'js', 'render']
		];
		if(preg_match_all('/\#([a-z0-9=,;\'%:]+)/i', $more, $result)){
			foreach ($result as $k => $v) {
				if($k == 0){
					foreach ($v as $key => $val) {
						$more = str_replace($val, null, $more);
					}
				}
				else{
					foreach ($v as $key => $val) {
						$ex = explode('=', $val);
						if(count($ex) == 2 && isset($options[$ex[0]])){
							$it = explode(';', $ex[1]);
							$options[$ex[0]] = $it;
						}
						elseif(count($ex) == 1){
							$it = explode(';', $ex[0]);
							$r = [];
							foreach ($it as $kk => $vv)
								if(in_array($vv, $options['generate'])) $r[] = $vv;
							$options['generate'] = $r;
						}
						else break;
					}
				}
			}
			// foreach ($variable as $key => $value) {
				// code...
			// }
		}
		$more = trim($more);
		$more_ = $more;
		$location = [
			'class' => ROOT . '\app\Components',
			'render' => ROOT . '\ressources\views\components',
			'css' => ROOT . '\public\assets\css\excepts\components',	
			'js' => ROOT . '\public\assets\js\excepts\components'	
		];
		$folder = [];
		$n = explode('/', $name);
		$c = array_pop($n);
		$namespace = '\\' . implode('\\', $n);
		$alias = trim($namespace, '\\') . $c;
		$classI = [
			'name' => $c,
			'folderPath' => $location['class'] . $namespace,
			'filePath' => $location['class'] . $namespace . '\\' . $c . '.php',
			'fileName' => $c . '.php',
		];
		$render = [
			'folderPath' => $location['render'] . strtolower($namespace),
			'filePath' => $location['render'] . strtolower($namespace) . '\\' . strtolower($c) . '.php',
			'fileName' => strtolower($c) . '.php'
		];
		$css = [
			'folderPath' => $location['css'] . strtolower($namespace),
			'filePath' => $location['css'] . strtolower($namespace) . '\\' . strtolower($c) . '.css',
			'fileName' => strtolower($c) . '.css'
		];
		$js = [
			'folderPath' => $location['js'] . strtolower($namespace),
			'filePath' => $location['js'] . strtolower($namespace) . '\\' . strtolower($c) . '.js',
			'fileName' => strtolower($c) . '.js'
		];

		$locate = str_replace('/', '.', strtolower($name));
		$class = 'component-' . str_replace('.', '-', $locate) . '-' . Component::classGen();


		$folder['class'] = $classI['folderPath'];
		$folder['render'] = $render['folderPath'];
		$folder['css'] = $css['folderPath'];
		$folder['js'] = $js['folderPath'];
		$more = explode(' ', $more);
		$m = [];
		foreach ($more as $vv) {
			if(!empty($vv)){
				$siu292 = explode('=', trim($vv, '-'));
				$ah82he2 = isset($siu292[1]) ? str_replace('%20', ' ', $siu292[1]) : null;
				$ah82he2 = is_null($ah82he2) ? null : ($ah82he2 == 'null' ? '!beNull' : ($ah82he2 == 'true' ? true : ($ah82he2 == 'false' ? false : $ah82he2)));
				$m[$siu292[0]] = $ah82he2;
			}
		}
		$more = $m;
		uasort($more, function ($a, $b) {
			return is_null($a) && is_null($b) ? 1 : (is_null($a) ? 1 : -1);
		});
		Component::folder($folder);
		$p = dirname(__DIR__) . '\storage\ComponentController';
		$sf = scandir($p); array_shift($sf); array_shift($sf);
		foreach ($sf as $f) {
			$k = pathinfo($f)['filename'];
			$file = $p . '\\' . $f;
			$content = null;
			if(in_array($k, $options['generate'])){
				switch ($k) {
					case 'class':
						copy($file, $classI['filePath']);
						$content = file_get_contents($classI['filePath']);
						$content = str_replace('@command', '// ' . \Enter::getCommandRoute(), $content);
						$content = str_replace('@namespace', $namespace, $content);
						$content = str_replace('@className', $classI['name'], $content);
						$content = str_replace('@locate', $locate, $content);
						if(!empty($more) && is_array($more)){
							$cx = ['id', 'class', 'attribute'];
							foreach ($more as $property => $vvbd) {
								$p00 = explode(':', $property);
								$prop = array_pop($p00);
								$type = implode('|', $p00);
								$type = empty($type) ? null : $type . ' ';

								if(in_array($prop, $cx)) continue;
								$content = str_replace('@moreInit', "private $" . $prop . ";\n\t@moreInit", $content);
								$pdji92 = $type . '$'. $prop . (is_null($vvbd) ? '' : ($vvbd === '!beNull' ? ' = null' : ' = ' . (
										is_string($vvbd) ? ($vvbd == "[]" && strpos($type, 'array') !== false ? "[]" : (is_numeric($vvbd) && strpos($type, 'int') !== false ? $vvbd : '"' . $vvbd . '"')) : ($vvbd === true ? "true" : ($vvbd === false ? "false" : $vvbd))
								))) . ', ';
								$content = str_replace('@moreParams', '@moreParams' . $pdji92, $content);
								$content = str_replace('@moreConst', '$this->'. $prop .' = $'. $prop . ";\n\t\t@moreConst", $content);
								$content = str_replace('@moreRender', '\'' . $prop . '\' => $this->' . $prop . ",\n\t\t\t@moreRender", $content);
							}
						}
						$content = str_replace('@moreInit', "", $content);
						$content = str_replace('@moreParams', "", $content);
						$content = str_replace('@moreConst', "", $content);
						$content = str_replace('@moreRender', "", $content);
						file_put_contents($classI['filePath'], $content);
						break;
					case 'render':
						$call = \Enter::more($more_, true, ['items' => function($key, $value){
							$sep = explode(':', $key);
							$k = array_pop($sep);
							$result['key'] = $key;
							$result['value'] = "'{$k}' => " . ($value == '!beNull' ? 'null' : (
								$value === null 
									? '...' 
									: (
										is_string($value) 
										? (
											(trim($value) == '[]' || preg_match('/\[(.*)\]|\[(.*)\]/i', trim($value))) && in_array('array', $sep) == true 
												? trim($value)
												: (
													is_numeric($value) && strpos($type, 'int') !== false 
													? $value 
													: '"' . $value . '"'
												)
										) 
										: (
											$value === true 
											? "true" 
											: ($value === false ? "false" : $value)
										)
									)
								)
							) . ', // ' . implode('|', $sep);
							return $result;
						}]);
						if(!empty($call)) $call = array_reverse($call);
						copy($file, $render['filePath']);
						$content = file_get_contents($render['filePath']);
						$content = str_replace('@namespace', $alias, $content);
						if(!empty($call))
							$content = str_replace('@component', 'component("' . $alias . "\", [\n\t\t\t" . (implode("\n\t\t\t", $call)) . "\n\t\t]);", $content);
						$content = str_replace('@class', $class, $content);
						file_put_contents($render['filePath'], $content);
						break;
					case 'js':
						copy($file, $js['filePath']);
						$content = file_get_contents($js['filePath']);
						$content = str_replace('@class', $class, $content);
						file_put_contents($js['filePath'], $content);
						break;
					case 'css':
						copy($file, $css['filePath']);
						$content = file_get_contents($css['filePath']);
						$content = str_replace('@class', $class, $content);
						file_put_contents($css['filePath'], $content);
						break;
				}
			}
		}

		// var_dump($css, $js, $render, $classI);
		// var_dump($location, $namespace, $className, $locate, $class);
		// $more = explode(' ', $more);
	}
	public function web($name){
		header('Content-Type: application/json');
		$dd = isset($_GET) ? $_GET : (isset($_POST) ? $_POST : null);
		if(is_array($dd) && $dd['datas']){
			$datas = json_decode($dd['datas'], true);
			$render = component($name, $datas, true, false, false);
			// header('Content-Type: text/json');
			$files = assetsCompnents(null, true);
			echo json_encode([
				'files' => $files,
				'render' => $render
			]);
			// var_dump(Component::getAllComponentsFiles());
			// var_dump($datas);
		}
		else echo json_encode(null);
	}
}