<?php 
namespace Scraps\Collects;
use Scraps\Factory\Factory;
use Support\Dir;
/**
 * 
 */
class Collect extends Factory{
	private $collects = [];
	
	public function __construct(){
		$this->init();
		// exit("dd");
		// exit();
	}
	protected function singleton_factory(){
		return true;
	}
	public function alternate(){
		return __DIR__ . '\\Factories';
	}

	private function init() : void {
		$cnf = config('collect');
		if($cnf && (!isset($cnf['#options']) || !isset($cnf['#options']['init']) || $cnf['#options']['init'] === true)){
			$this->exceptions($cnf);
			$this->orders($cnf);
			foreach ($cnf as $key => $value) {
				if($key != '#options') $res = $this->addCollect($key, $value);
			}
		}
	}

	public function addCollect(string|null $key = null, array|string $value){
		$value = is_string($value) ? ['path' => $value] : $value;
		$res = false;
		if(!empty($value) && is_array($value) && isset($value['path'])){
			$value['path'] = str_replace('/', '\\', $value['path']);
			$extension = (isset($value['extension']) && (is_array($value['extension']) || is_string($value['extension'])) ? $value['extension'] : null);
			$this->collects[$key] = ['path' => $value['path']];
			$this->collects[$key]['exceptions'] = isset($value['exceptions']) ? (is_array($value['exceptions']) ? $value['exceptions'] : (is_string($value['exceptions']) ? [$value['exceptions']] : $this->exceptions()->getGeneralException())) : $this->exceptions()->getGeneralException(); //exp
			$this->collects[$key]['orders'] = isset($value['orders']) ? (is_array($value['orders']) ? $value['orders'] : (is_string($value['orders']) ? [$value['orders']] : $this->orders()->getGeneralOrder())) : $this->orders()->getGeneralOrder();;
			$this->collects[$key]['extension'] = $extension;
			$this->collects[$key]['files'] = ['exceptions' => [], 'ready' => []];
			$res = $this;
		}
		return $res;
	}
	public function setCollect(string|null $key = null, array|bool $value){
		$res = false;
		if(isset($this->collects[$key]) && is_array($value)){
			$this->collects[$key] = $value;
			$res = $this;
		}
		return $res;
	}
	public function getCollect(string|null $k = null, $init = true, array|string $add_patterns = [], bool|array|string $order = true){
		$res = false;
		if(!empty($this->collects)){
			if(is_null($k)){
				if($init === true){
					foreach ($this->collects as $key => $value){
						$this->generateFiles($key, $value); // Génère les fichiers
					}
					if(!empty($add_patterns)) $this->collect_add_exceptions($add_patterns); // Ajout additionnel des fichiers
					if(!empty($this->getGeneralOrder()) && $order !== false){
						foreach ($this->collects as $key => $value) $this->collect_re_order($key, $order); // Ordres de priorité
					}
				}
				$res = $this->collects;
			}
			elseif(isset($this->collects[$k])){
				if($init === true){
					$this->generateFiles($k, $this->collects[$k]); // Génère les fichiers
					if(!empty($add_patterns)) $this->collect_add_exceptions($add_patterns, $k); // Ajout additionnel des fichiers
					if(!empty($this->getGeneralOrder()) && $order !== false) $this->collect_re_order($k, $order); // Ordres de priorité
				}
				$res = $this->collects[$k];
			}
		}
		return $res;
	}
	private function generateFiles($key, $value){
		$value['path'] = str_replace('/', '\\', $value['path']);
		$extension = (isset($value['extension']) && (is_array($value['extension']) || is_string($value['extension'])) ? $value['extension'] : null);
		$this->collects[$key]['files']['ready'] = Dir::scandir($value['path'], true)->scan(null, $extension, null, true, false, null, true, $this->collects[$key]['exceptions']);
		$this->collects[$key]['files']['exceptions'] = Dir::scandir($value['path'], true)->scan(null, $extension, null, true, false, null, true, $this->collects[$key]['exceptions'], false, true);
	}
	private function collect_re_order(string|null $key = null, bool|array|string $order = true){
		$order_pattern = $order === true ? (isset($this->collects[$key]['orders']) ? (is_array($this->collects[$key]['orders']) ? $this->collects[$key]['orders'] : [$this->collects[$key]['orders']]) :$this->getGeneralOrder()) : (is_array($order) ? $order : [$order]);
		$order_pattern = !isset($order_pattern['pattern_file']) ? ['pattern_file' => (is_array($order_pattern) ? $order_pattern : [$order_pattern])] : $order_pattern;
		$order_pattern['pattern_file']['reverse'] = isset($order_pattern['pattern_file']['reverse']) && is_bool($order_pattern['pattern_file']['reverse']) ? $order_pattern['pattern_file']['reverse'] : $this->getGeneralOrder('reverse');
		$order_pattern['pattern_file']['repeat'] = isset($order_pattern['pattern_file']['repeat']) && is_bool($order_pattern['pattern_file']['repeat']) ? $order_pattern['pattern_file']['repeat'] : $this->getGeneralOrder('repeat');
		foreach(['ready', 'exceptions'] as $type){
			$order_element = [];
			$principal_ready = $this->collects[$key]['files'][$type];
			foreach ($order_pattern['pattern_file'] as $pattern__) {
				if(is_string($pattern__)){
					foreach($this->collects[$key]['files'][$type] as $kk => $vall){
						if(Dir::_preg_v($vall['filepath'], $pattern__)){
							if(in_array($vall, $order_element) && $order_pattern['pattern_file']['repeat'] === true)
								$order_element = array_remove($vall, $order_element);
							if(!in_array($vall, $order_element))
								$order_element[] = $vall;
							$principal_ready = array_remove($vall, $principal_ready);
						}
					}
				}
			}
			// var_dump($order_element);exit();
			$principal_ready = $order_pattern['pattern_file']['reverse'] === true ? array_merge_recursive($principal_ready, $order_element) : array_merge_recursive($order_element, $principal_ready);
			$this->collects[$key]['files'][$type] = $principal_ready != $this->collects[$key]['files']['ready'] ? $principal_ready : $this->collects[$key]['files']['ready'];
			// // $this->collects[$key]['files'][$type] = $principal_ready;
			// // var_dump($order_pattern['pattern_file']['reverse'], '____________________________');
			// echo "Order : " . $type . "\n";
			// var_dump((function($r){$t = [];foreach($r as $k => $v){$t[$k] = $v['filepath'];} return $t;})($principal_ready), "\n\n\n");
			// // var_dump($order_element, '____________________________');
		}
	}
	private function collect_add_exceptions(array $patterns, string|null $key = null){
		if(!empty($key) && isset($this->collects[$key])){
			// var_dump($this->collects[$key]['files']['exceptions']); // 13

			foreach ($this->collects[$key]['files']['exceptions'] as $k => $value) {
				if(!in_array($value['filepath'], $this->collects[$key]['files']['ready']) && Dir::preg_v($value['filepath'], $patterns)){
					$this->collects[$key]['files']['ready'][] = $value;
					$this->collects[$key]['files']['exceptions'] = array_remove($value, $this->collects[$key]['files']['exceptions']);
				}
			}
			// var_dump(count($this->collects[$key]['files']['ready'])); // 3++ -> 4
			// var_dump(count($this->collects[$key]['files']['exceptions'])); // 13-- -> 12
			// var_dump(array_remove('aa', ['aa', 'bbl', 'suw']));
			// exit();
		}
		else{
			foreach ($this->collects as $key => $value) {
				foreach ($this->collects[$key]['files']['exceptions'] as $k => $value) {
					if(!in_array($value['filepath'], $this->collects[$key]['files']['ready']) && Dir::preg_v($value['filepath'], $patterns)){
						$this->collects[$key]['files']['ready'][] = $value;
						$this->collects[$key]['files']['exceptions'] = array_remove($value, $this->collects[$key]['files']['exceptions']);
					}
				}
			}
		}
	}

	public function getInstance(){ return $this; }

	protected function getFactorySpace(){ return 'Scraps\Collects\Factories\\'; }

}
/*
Collect::getCollect('assets_css');
var_dump(Collect::getCollect(null, false));


// Collect::getCollect('assets_css');
Collect::addCollect('asset_css', [
	'path' => ROOT . '\\' . trim(trim('public/assets/css', "/"), '\\'),
	'extension' => 'css',
	'exceptions' => Collect::exceptionMerge(['/\\\layouts\\\/'])
]);
var_dump(Collect::getCollect());


*/