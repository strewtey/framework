<?php 
namespace Support;
/**
 * 
 */
class Dir extends Support
{
	protected static $instance;
	public $path;
	public const DIR_ONLY = 4078;
	
	// ex Dir::scan([ROOT, ROOT . '/public'], null, null, null, null, null, null, null, File::class)->lorem('');
	public function _scan(
		string|array|null $path = null, // 1
		string|array|null $ext = null, //   2
		bool|null $merge = true, //          3
		bool|null $location = false, //       4
		bool|null $folder = false, //          5
		string|int|null $sort = null, //        6
		bool|null $infos = false, //             7
		string|array|null $exceptions = null, // 8
		string|bool|null $combine = false, //    9
		bool|null $get_exc = false //            10
	){
		if($this->combine == null && $path == null) die('Verify your path !');
		if($this->combine != null && isset($this->combine['datas'])){
			$ns = __NAMESPACE__ . '\\';
			if(isset($this->combine['methodclass']) && $this->combine['methodclass'] == ($ns . 'Dir::_scanDir')){
				$path = array_merge($this->combine['datas']['path'], $this->combine['result']);
			}
			else{
				if(isset($this->combine['datas']['path']) && (is_string($this->combine['datas']['path']) || is_array($this->combine['datas']['path']))) {
					$path = $this->combine['datas']['path'];
					if(isset($this->combine['datas']['ext']) && (is_string($this->combine['datas']['ext']) || is_array($this->combine['datas']['ext']) || is_null($this->combine['datas']['ext']))) $ext = $this->combine['datas']['ext'];
					if(isset($this->combine['datas']['merge']) && (is_bool($this->combine['datas']['merge']) || is_null($this->combine['datas']['merge']))) $merge = $this->combine['datas']['merge'];
					if(isset($this->combine['datas']['location']) && (is_bool($this->combine['datas']['location']) || is_null($this->combine['datas']['location']))) $location = $this->combine['datas']['location'];
					if(isset($this->combine['datas']['folder']) && (is_bool($this->combine['datas']['folder']) || is_null($this->combine['datas']['folder']))) $folder = $this->combine['datas']['folder'];
					if(isset($this->combine['datas']['sort']) && (is_string($this->combine['datas']['sort']) || is_int($this->combine['datas']['sort']) || is_null($this->combine['datas']['sort']))) $sort = $this->combine['datas']['sort'];
					if(isset($this->combine['datas']['infos']) && (is_bool($this->combine['datas']['infos']) || is_null($this->combine['datas']['infos']))) $infos = $this->combine['datas']['infos'];
					if(isset($this->combine['datas']['exceptions']) && (is_string($this->combine['datas']['exceptions']) || is_array($this->combine['datas']['exceptions']) || is_null($this->combine['datas']['exceptions']))) $exceptions = $this->combine['datas']['exceptions'];
				}
			}
			$this->combine = null;
		}
		// if(is_null($path)) die('Verify your path !');
		$path = is_string($path) ? [$path] : $path;
		$ext = is_string($ext) ? [$ext] : (is_null($ext) ? [] : $ext);
		$merge = is_null($merge) ? true : $merge;
		$location = is_null($location) ? false : $location;
			if(count($path) > 1 && $merge == true) $location = true;
		$folder = is_null($folder) ? false : $folder;
		$sort = strtolower($sort);
			switch ($sort) {
				// case 'asc': $sort = SCANDIR_SORT_ASCENDING; break;
				// case 'a': $sort = SCANDIR_SORT_ASCENDING; break;
				// case 0: $sort = SCANDIR_SORT_ASCENDING; break;

				case 'desc': $sort = SCANDIR_SORT_DESCENDING; break;
				case 'd': $sort = SCANDIR_SORT_DESCENDING; break;
				case 1: $sort = SCANDIR_SORT_DESCENDING; break;

				case 'none': $sort = SCANDIR_SORT_NONE; break;
				case 'default': $sort = SCANDIR_SORT_NONE; break;
				case 'n': $sort = SCANDIR_SORT_NONE; break;
				case 'd': $sort = SCANDIR_SORT_NONE; break;
				case 2: $sort = SCANDIR_SORT_NONE; break;

				default: $sort = SCANDIR_SORT_ASCENDING; break;
			}
		$infos = is_null($infos) ? false : $infos;
		$exceptions = is_string($exceptions) ? [$exceptions] : (is_null($exceptions) ? [] : $exceptions);
			array_unshift($exceptions, "/^\\\.$/", "/^\\\.\\\.$/");
		$dirScan = null;
		foreach ($path as $key => $element) {
			if(file_exists($element) && is_dir($element)){
				$dirScan[$key] = ['path' => str_replace('/', '\\', $element), 'elements' => scandir($element, $sort)];
			}
			else die((file_exists($element) && !is_dir($element) ? "This path \"" . $element . "\" is not \"Folder\"" : "This path \"" . $element . "\" is doesn't exists") . " !");
		}
		$res = false;
		if(!empty($dirScan) && ($res = $this->scanAnalyze($dirScan, $ext, $merge, $location, $folder, $infos, $exceptions, $get_exc)) != false){
			if($combine === true || is_string($combine)){
				$this->combine['methodclass'] = __METHOD__;
				$this->combine['datas'] = [
					'path' => $path,
					'ext' => $ext,
					'merge' => $merge,
					'location' => $location,
					'folder' => $folder,
					'sort' => $sort,
					'infos' => $infos,
					'exceptions' => $exceptions
				];
				$this->combine['result'] = $res;
				$class = $combine;
				if(class_exists($class)){
					return $this->initCombine($class, true);
				}
				return $this;
			}
			else return $res;
		}
		else{
			if($res === false) die('Verify your path !');
			else return $res;
		}
	}
	protected function scanAnalyze(array $elements, $ext, $merge, $location, $folder, $infos, $exceptions, $get_exc){
		$res = false;
		foreach ($elements as $k => $value) {
			$emp = null; $e = trim($value['path'], '\\') . '\\'; // $emp = null;$e = $value['path'] . '\\';
			if($location == true) $emp = $e;
			if($merge == false)
				$res[$k] = [];
			else{
				if($res == false) $res = [];
			}
			foreach ($value['elements'] as $key => $val) {
				if(((($folder == true && is_dir($e . $val)) && empty($ext)) || is_file($e . $val)) && (empty($ext) || (!empty($ext) && is_file($e . $val) && in_array(File::extractExtension($val), $ext))) && (($folder == 4 && is_dir($e . $val)) || $folder != 4)){
					$do = $emp . $val;
					if($infos == true){
						$a = pathinfo($e . $val);
						$a['filepath'] = $e . $val;
						$a['filesize'] = filesize($e . $val);
						$a['mimetype'] = is_dir($e . $val) ? 'directory' : File::mime($e . $val);
						$do = $a;
					}
					$exc_ = $get_exc === false || is_null($get_exc) ? !self::preg_v(($e . $val), $exceptions) : self::preg_v(($e . $val), $exceptions);
					if($merge == false && $exc_){
						$res[$k][] = $do;
					}
					elseif($merge == true && $exc_){
						$res[] = $do;
					}
				}
			}
		}
		return $res;
	}
	public static function _preg_v(string $val, array|string $pattern){
		$pattern = is_array($pattern) ? $pattern : [$pattern];
		$res = false;
		foreach ($pattern as $patt) {
			if(is_string($patt) && preg_match($patt, $val)){
				$res = true;
				break;
			}
		}
		return $res;
		// preg_match($pattern, $val)
	}
	public function _scanDir(string|array $path, $combine = false){
		if($this->combine != null && isset($this->combine['datas'])){
			$ns = __NAMESPACE__ . '\\';
			if(isset($this->combine['class']) && $this->combine['class'] == ($ns . 'a')){}
			$this->combine = null;
		}
		$path = is_string($path) ? [$path] : $path;
		$res = [];
		foreach ($path as $k => $v) {
			if(!in_array($v, $res)){
				$d = self::sc($v, true);
				if($d !== false){
					foreach ($d as $key => $value) {
						$e = $v . '\\' . $value;
						if(!in_array($e, $res)){
							$res[] = $e;
							if(!empty($x = $this->_scanDir($e))){
								$res = array_merge($res, $x);
							}
						}
					}
				} else return false;
			}
		}
		if($combine === true || is_string($combine)){
			$this->combine['methodclass'] = __METHOD__;
			$this->combine['datas'] = [
				'path' => $path
			];
			$this->combine['result'] = $res;
			$class = $combine;
			if(class_exists($class)){
				return $this->initCombine($class, true);
			}
			return $this;
		}
		else return $res;
	}
	private static function sc(string $path, $df = false, $exceptions = ['.', '..']){
		if(file_exists($path)) {
			$d = scandir($path);
			$res = [];
			foreach ($d as $key => $value) {
				if(!in_array($value, $exceptions)){
					if($df === false || ($df === true && is_dir($path . '\\' . $value)) || ($df === null && is_file($path . '\\' . $value)))
						$res[] = $value;
				}
			}
			return $res;

		}
		return false;
	}
}

// function ($service) use ($callback) {
// 	$callback($service);
// }




// Test ----
// $a = Dir::val(2, true)->plus(4, true)->plus(4);
// Dir::scan(Dir::scanDir([ROOT, ROOT . '\\core']))
// Dir::scanDir([ROOT, ROOT . '\\core'], true)->scan(null, ['php', 'json', 'css'])
// Dir::scanDir([ROOT, ROOT . '\\core'], true)->scan()
// Dir::scanDir([ROOT, ROOT . '\\core'])
// ------
	// public function _val(int $nbr = null, $combine = false){
	// 	if($combine){
	// 		$this->combine = $nbr;
	// 		return $this;
	// 	}
	// 	elseif($combine == false) return $nbr;
	// 	elseif(is_callable($combine)) return call_user_func($combine);
	// }
	// public function _plus(int $nbr = null, $combine = false){
	// 	if($this->combine != null){
	// 		$nbr += $this->combine;
	// 	}
	// 	if($combine){
	// 		$this->combine = $nbr;
	// 		return $this;
	// 	}
	// 	elseif($combine == false) return $nbr;
	// 	elseif(is_callable($combine)) return call_user_func($combine);
	// }