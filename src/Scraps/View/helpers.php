<?php
use Support\Dir;
if (!function_exists('area')) {
	function area($name = null, $val = null){
		if(is_null($name))
			ob_start();
		else{
			if(is_null($val))
				return isset(View::getRender('area')[$name]) ? View::getRender('area')[$name] : null;
			else
				View::setRender('area', $val, $name);
			// return isset(View::getRender('area')[$name]) ? View::getRender('area')[$name] : null;
		}
	}
}
if (!function_exists('area_s')) {
	function area_s(string $name){
		View::setRender('area', ob_get_clean(), $name);
	}
}
if (!function_exists('layout')) {
	function layout(string $file, array $datas = [], $p = false){
		$p = (($p === true) ? $file : '/layouts/' . $file);
		$file = file_exists($file) ? $p : View::s_getRessourcesViewPath() . $p;
		if(file_exists($file))
			View::setRender('layout', ['file' => $file, 'datas' => $datas]);
		else
			die("Your layout file \"" . $file . "\" doesn't exists !");
	}
}
if (!function_exists('entity')) {
	function entity(string $name, $val = null, bool $push = true){
		if(is_null($val))
			return isset(View::getRender('entity')[$name]) ? View::getRender('entity')[$name] : null;
		else{
			View::setRender('entity', $val, $name, $push);
		}
	}
}
if (!function_exists('title')) {
	function title(string $val = null, bool $v = false){
		if(is_null($val)){
			$ti = View::getRender('entity');
			return $ti != false && isset($ti['title']) ? View::getRender('entity')['title'] : (!is_null(config('title')) ? config('title') : config('name'));
		}
		else{
			View::setRender('entity', ['title' => $val]);
		}
	}
}
if (!function_exists('incl')) {
	function incl($file, array $datas = [], bool $str = false){
		$file = !file_exists($file) ? View::s_getRessourcesViewPath() . '/' . str_replace('.', '/', $file) . '.php' : $file;
		if(file_exists($file)){
			if(!empty($datas)) extract($datas);
			if($str === true) ob_start();
			require $file;
			if($str === true) return ob_get_clean();
		}
		// else
		// 	die('This file "' . $file . '"');
	}
}
if (!function_exists('generate')) {
	function generate(string $extension, string|array|null $folder = null, $maq = null, $header = null, bool|string|array $excepts = true, array $order = []){
		$folder = is_array($folder) ? $folder : (is_null($folder) ? null : [$folder]);
		$mac = $extension == 'css' 
			? '<link rel="stylesheet" type="text/css" href="--link--">'
			: ($extension == 'js'
				? '<script type="text/javascript" src="--link--"></script>'
				: (!is_null($maq)
					? $maq
					: die('Your type "' . $type . '" is not availible !')
				)
			)
		;
		$loc = ROOT . '\public\assets';
		$res = '';
		if(!is_null($folder)){
			$dej = [];
			foreach ($folder as $key => $value) {
				if(file_exists($value) && is_dir($value) && !in_array($value, $dej)){
					$loc = str_replace('/', '\\', $value);
					$files = Dir::scandir($loc, true)->scan(null, $extension, null, true);
					foreach ($files as $key => $value) {
						$f = str_replace('\\', '/', assets(trim(str_replace(ROOT . '\public\assets', '', $value), '\\')));
						if((preg_match('/\/excepts\//', $f) && $excepts === true) || ((is_string($excepts) || is_array($excepts)) && (function($pattern, $val){
							$pattern = is_array($pattern) ? $pattern : [$pattern];
							$res = false;
							foreach ($pattern as $patt) {
								if(preg_match($patt, $val)){
									$res = true;
									break;
								}
							}
							return $res;
							// preg_match($pattern, $val)
						})($excepts, $f) === true)) continue;
						if($extension == 'js' && $header === false && preg_match('/\/header\//', $f))
							continue;
						elseif($extension == 'js' && $header === true && !preg_match('/\/header\//', $f))
							continue;
						$res .= str_replace('--link--', $f, $mac);
					}
					$dej[] = $value;
				}
			}
		}
		else{
			if(file_exists($loc) && is_dir($loc)){
				$files = Dir::scandir($loc, true)->scan(null, $extension, null, true);
				foreach ($files as $key => $value) {
					$f = str_replace('\\', '/', assets(trim(str_replace($loc, '', $value), '\\')));
						if((preg_match('/\/excepts\//', $f) && $excepts === true) || ((is_string($excepts) || is_array($excepts)) && (function($pattern, $val){
							$pattern = is_array($pattern) ? $pattern : [$pattern];
							$res = false;
							foreach ($pattern as $patt) {
								if(preg_match($patt, $val)){
									$res = true;
									break;
								}
							}
							return $res;
							// preg_match($pattern, $val)
						})($excepts, $f) === true)) continue;
					if($extension == 'js' && $header === false && preg_match('/\/header\//', $f))
						continue;
					elseif($extension == 'js' && $header === true && !preg_match('/\/header\//', $f))
						continue;
					$res .= str_replace('--link--', $f, $mac);
				}
			}
			else die('This default folder "'.$loc.'" not exists');
		}
		return $res;
	}
}