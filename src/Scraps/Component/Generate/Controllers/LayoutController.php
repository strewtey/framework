<?php 
namespace Scraps\Component\Generate\Controllers;
use Scraps\Component\Component;
use Enter;
/**
 * 
 */
class LayoutController /*extends Controller*/{

	public function __construct(){ /*...*/ }

	public function index($path, $more = null){
		$more = Enter::more($more);
		$location = [
			'render' => ROOT . '\ressources\views\layouts',
			'css' => ROOT . '\public\assets\css\excepts\layouts',	
			'js' => ROOT . '\public\assets\js\excepts\layouts'	
		];
		$folder = [];
		$n = explode('/', $path);
		$c = array_pop($n);
		$namespace = '\\' . implode('\\', $n);
		$alias = trim($namespace, '\\') . $c;
		$nV = strtolower($namespace) == '\\' ? null : strtolower($namespace);
		$render = [
			'folderPath' => $location['render'] . strtolower($namespace),
			'filePath' => $location['render'] . $nV . '\\' . strtolower($c) . '.php',
			'fileName' => strtolower($c) . '.php'
		];
		$css = [
			'folderPath' => $location['css'] . strtolower($namespace),
			'filePath' => $location['css'] . $nV . '\\' . strtolower($c) . '.css',
			'fileName' => strtolower($c) . '.css'
		];
		$js = [
			'folderPath' => $location['js'] . strtolower($namespace),
			'filePath' => $location['js'] . $nV . '\\' . strtolower($c) . '.js',
			'fileName' => strtolower($c) . '.js'
		];

		$locate = str_replace('/', '.', strtolower($path));
		$class = 'layout-' . str_replace('.', '-', $locate) . '-' . Component::classGen();


		$folder['render'] = $render['folderPath'];
		$folder['css'] = $css['folderPath'];
		$folder['js'] = $js['folderPath'];

		Component::folder($folder);
		$p = dirname(__DIR__) . '\storage\LayoutController';
		$sf = scandir($p); array_shift($sf); array_shift($sf);
		foreach ($sf as $f) {
			$k = pathinfo($f)['filename'];
			$file = $p . '\\' . $f;
			$content = null;
			switch ($k) {
				case 'render':
					copy($file, $render['filePath']);
					$content = file_get_contents($render['filePath']);
					$content = str_replace('@command', \Enter::getCommandRoute(), $content);
					$content = str_replace('@locate_css', str_replace('\\', '/', trim($nV . '\\' . strtolower($c), '\\')), $content);
					$content = str_replace('@locate_js', str_replace('\\', '/', trim($nV . '\\' . strtolower($c), '\\')), $content);
					file_put_contents($render['filePath'], $content);
					break;
			}
		}
		// case 'js':
		copy(dirname(__DIR__) . '\storage\ComponentController\js.php', $js['filePath']);
		$content = file_get_contents($js['filePath']);
		$content = str_replace('@class', $class, $content);
		file_put_contents($js['filePath'], $content);
		// case 'css':
		copy(dirname(__DIR__) . '\storage\ComponentController\css.php', $css['filePath']);
		$content = file_get_contents($css['filePath']);
		$content = str_replace('@class', $class, $content);
		file_put_contents($css['filePath'], $content);
	}
}