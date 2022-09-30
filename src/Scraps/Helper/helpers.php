<?php 
use Scraps\Helper\Helper;
use App\Lang\Lang;
use Scraps\ResponseText\Response;

// use Scraps\Config\Config;

if (!function_exists('config')) {
	function config($key = null, $default = null){
		if(is_array($key))
			return Config::set($key, $default);
		return Config::get($key, $default);
	}
}
if (!function_exists('switchPoint')) {
	function switchPoint(string $path){
		return str_replace('.', '/', trim($path, '.'));
	}
}
if (!function_exists('url')) {
	function url($path = null, array $params = [], bool $get = true, bool $get_array = true){
		if(!is_null($path))
			return Route::urlGenerator()->host($path, $params, $get, $get_array);
		return Route::urlGenerator();
	}
}
if (!function_exists('view')) {
	function view($path, $datas = [], $layout = null, bool $render = false){
		if($render === true) ob_start();
		View::render($path, $datas, $layout);
		if($render === true) return ob_get_clean();
	}
}
if (!function_exists('view_exist')) {
	function view_exist($path){
		return file_exists(View::s_getRessourcesViewPath() . '\\' . switchPoint($path) . '.php');
	}
}
if (!function_exists('assets')) {
	function assets($path, array $params = [], $get = false, bool $get_array = true){
		return url()->host((preg_match('/^(.*?)xampp\/htdocs$/', $_SERVER['DOCUMENT_ROOT']) || preg_match('/^(.*?)lampp\/htdocs$/', $_SERVER['DOCUMENT_ROOT']) || preg_match('/^(.*?)mampp\/htdocs$/', $_SERVER['DOCUMENT_ROOT']) || preg_match('/^(.*?)wampp\/htdocs$/', $_SERVER['DOCUMENT_ROOT']) || preg_match('/^(.*?)wampp32\/htdocs$/', $_SERVER['DOCUMENT_ROOT']) || preg_match('/^(.*?)wampp64\/htdocs$/', $_SERVER['DOCUMENT_ROOT']) ? 'public/assets/' : 'assets/') . $path, $params, $get, $get_array);
	}
}
if (!function_exists('cdn')) {
	function cdn($path){
		return config('cdn') . '/' . trim($path, '/');
	}
}
if (!function_exists('crypty')) {
	function crypty(string $str){
		return Crypt::crypty($str)->crypt();
	}
}
if (!function_exists('uncrypty')) {
	function uncrypty(string $str){
		return Crypt::crypty($str)->decrypt();
	}
}
if (!function_exists('lang')) {
	function lang(string $str = null){
		return is_null($str) ? Lang::get() : Lang::set($str);
	}
}
if (!function_exists('lang_exists')) {
	function lang_exists(string $str){
		return Lang::exists($str);
	}
}
// response('welcome:a')
if (!function_exists('response')) {
	function response(string|callable $str, $lang = null, string $fun = null){
		$res = Response::res($str, $lang);
		if(!is_null($fun) && function_exists($fun)){
			$res = call_user_func($fun, $res);
		}
		return $res;
	}
}
if (!function_exists('cookie')) {
	function cookie(string $name = null, string $value = null, string|array $expires = [1, 'mo']){
		if(is_null($value)) return is_null($name) ? Cookie::getAllCookies() : Cookie::getAllCookies($name);
		else return Cookie::create($name, $value, $expires);
	}
}
if (!function_exists('cookie_has')) {
	function cookie_has(string $name){
		return Cookie::has($name);
	}
}
if (!function_exists('route')) {
	function route(string $name, $datas = [], $get = true){
		return Route::named($name, $datas, $get);
	}
}
if (!function_exists('current_request')) {
	function current_request(){
		return Route::getCurrentRequest();
	}
}
if (!function_exists('component')) {
	function component(string $component, array $datas = [], bool $ret = false, $class = false, bool $except = true){
		return Component::componentRender($component, $datas, $ret, $class, $except);
	}
}
if (!function_exists('assetsCompnents')) {
	function assetsCompnents(string $as = null, bool $files = false, bool $ret = false){
		$r = Component::assetRender($as, $files, $ret);
		return $r;
	}
}
if (!function_exists('redirect')) {
	function redirect(string $url, $datas = [], $get = true){
		header('Location: ' . (route($url, $datas, $get) ? route($url, $datas, $get) : url($url, $datas, $get)));
	}
}
if (!function_exists('lorem')) {
	function lorem(int|null $i = null, array|int|null $multiple = null, Closure|string|null $callable = null){
		$l = ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae vulputate mauris. Nunc vestibulum lorem malesuada, imperdiet magna vel, faucibus sem. Phasellus vulputate ipsum ac sapien pretium, ac aliquet elit ultricies. Suspendisse potenti. Donec aliquet faucibus ipsum, vel volutpat lectus rutrum quis. Praesent fermentum arcu vel lorem porttitor tempus. Proin sollicitudin nulla sit amet justo sollicitudin, in ultrices lorem facilisis. Cras varius lobortis ipsum non iaculis. Aliquam viverra dolor purus, tempus placerat dui facilisis eget. In varius lorem quis est vehicula placerat. Sed varius lobortis leo, eu vehicula libero faucibus vitae. Praesent pharetra odio quam, suscipit interdum turpis vehicula a. Aliquam sit amet dui scelerisque, interdum magna eu, dictum purus. Suspendisse orci felis, varius ac bibendum et, tincidunt vitae enim. Maecenas venenatis lacus ut ex porta, eget posuere enim interdum. Sed eget nunc ornare, rhoncus felis id, mollis metus. Quisque non cursus erat, congue ullamcorper purus. Nunc faucibus est ut euismod laoreet. Proin pharetra arcu eget leo eleifend porttitor. Nulla sit amet nulla leo. Sed blandit sapien at mi rhoncus, non pharetra turpis imperdiet. Curabitur feugiat ligula id lacus dictum, sed lacinia libero lacinia. Curabitur lacinia nec velit quis ornare. Suspendisse ut hendrerit mi. Nullam vestibulum tortor nunc, sed tristique erat tempus eget. Aenean orci lorem, pretium et quam vel, sodales tincidunt dolor. Pellentesque euismod, enim nec rutrum vestibulum, enim sem mattis erat, rutrum venenatis lacus nibh sit amet lectus. Nulla facilisi. Donec accumsan non tortor ut maximus. Integer consectetur eros lacus. Fusce eros mi, vestibulum vitae sem non, egestas iaculis arcu. Aliquam quam metus, tempor non lobortis ac, maximus vel est. Aenean lacinia convallis diam vel finibus. Pellentesque tincidunt quam eget libero suscipit pellentesque. Cras ut leo nulla. Pellentesque vitae dui in augue pretium imperdiet. Nam vel quam libero. Curabitur sollicitudin, urna at blandit viverra, sapien massa molestie leo, sit amet iaculis diam odio iaculis urna. Aenean lorem nisi, facilisis id vehicula et, aliquet quis leo. In ullamcorper dui eget tortor hendrerit, vitae commodo diam vulputate. Maecenas est leo, consectetur ut odio molestie, sollicitudin semper est. Phasellus in sollicitudin eros. Nam feugiat bibendum augue et elementum. Integer pretium cursus mauris sit amet ultricies. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Donec rutrum cursus lorem non condimentum. Quisque tempus lectus lacus, at porta nisi auctor nec. Duis scelerisque placerat elit, quis eleifend elit pretium et. Quisque auctor, metus sed consectetur mattis, augue enim iaculis lectus, id aliquam odio ante et eros. Proin elementum volutpat lacus in tempus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris varius purus sed est maximus euismod. Cras a accumsan justo. Nunc ac dapibus enim. Pellentesque fermentum purus vitae nibh sollicitudin molestie. Duis laoreet justo sed ante imperdiet luctus. Maecenas et mattis turpis. Aliquam sollicitudin quam eget nisi vulputate maximus. Maecenas enim est, ultrices vitae tortor non, finibus pretium augue. Donec dapibus finibus sem id cursus. Duis id lorem sit amet odio pretium accumsan a vitae justo. Aliquam erat volutpat. Pellentesque vehicula justo dolor, et consectetur ipsum elementum quis. Maecenas nibh orci, gravida porta aliquet non, molestie eget orci. Cras et ipsum bibendum, varius ex vitae, blandit nisl. Proin congue justo id sem gravida, at rhoncus dui tincidunt. Nulla tincidunt risus quis odio malesuada, ac semper sem tristique. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Pellentesque massa nulla, venenatis non pellentesque et, eleifend ac nibh. Pellentesque erat turpis, ultricies at blandit eget, porta ut tortor. In vitae quam lectus. Duis sit amet sagittis urna, nec luctus arcu. Integer in ex laoreet, condimentum lacus et, euismod ligula. Ut a porta risus, et malesuada lectus. Integer venenatis, sapien non molestie sagittis, velit neque varius ante, fringilla ullamcorper ligula est molestie dolor. Morbi id sodales justo. Maecenas ac nisl commodo, varius purus ut, malesuada lacus. Suspendisse nunc purus, faucibus eget velit nec, dictum auctor ipsum. Duis est velit, mollis et enim eu, maximus porttitor sapien. Morbi vel ipsum aliquam dolor scelerisque eleifend nec ac mi. Phasellus vitae orci ut tellus lobortis rhoncus eget vel eros. Curabitur elit neque, egestas vel mi eu, imperdiet rutrum ante. Quisque blandit orci sit amet tincidunt volutpat. Aliquam erat volutpat. Suspendisse et condimentum justo, elementum placerat orci. Nulla facilisi. Maecenas dictum quam purus, sed ornare lorem sagittis ut. Proin vulputate nulla urna. Nunc vel velit laoreet, cursus ante nec, placerat orci. Quisque mi ligula, dapibus eget placerat ac, sodales quis est. Aenean metus mi, condimentum et vehicula in, suscipit bibendum justo. Vestibulum scelerisque fringilla malesuada. Curabitur a nunc vitae magna pretium accumsan a at ipsum. Curabitur consequat in ipsum sit amet elementum. Phasellus nec sapien eu quam tempor vehicula. Nam accumsan ante et suscipit feugiat. Sed fringilla urna eget tortor auctor tincidunt. Suspendisse vitae orci in ante ullamcorper rutrum at a risus. Mauris feugiat eu ipsum vitae accumsan. Maecenas tincidunt nisl neque, id volutpat lectus finibus quis. Donec elementum ante elit, eget vestibulum dui porta sed. Curabitur tincidunt vitae lectus et blandit. Morbi imperdiet efficitur quam. Donec eleifend a est in vestibulum. Integer tempus ut ipsum commodo finibus. Integer cursus mollis ante, vel convallis dolor accumsan quis. Vivamus consequat dictum ipsum, vitae varius erat ultrices non. Sed varius odio fermentum est pellentesque suscipit. Sed aliquam ipsum et nibh porttitor, id tempor nulla ullamcorper. Sed non lorem accumsan, dignissim purus a, molestie lacus. Donec ante nisl, vulputate vel sagittis nec, auctor quis elit. Aliquam consequat quam laoreet, faucibus magna non, dapibus sapien. Quisque sagittis orci et libero placerat, quis eleifend elit fermentum. Aliquam ultricies lacus vitae purus sollicitudin, nec facilisis velit rhoncus. Vivamus ut arcu eu justo blandit posuere. Nullam vestibulum eros sed leo eleifend feugiat. Curabitur laoreet bibendum tellus, vel consectetur nibh aliquam congue. Morbi vitae sollicitudin erat. Nulla ante nunc, condimentum sit amet laoreet vitae, aliquet ac ligula. Aenean dignissim eget tortor quis efficitur. Praesent venenatis orci sit amet dolor lobortis, finibus pretium velit egestas. Vestibulum sit amet orci odio. Praesent eu commodo libero. Morbi sit amet risus sit amet ex rhoncus cursus. Duis imperdiet nisl ut purus pretium, consectetur hendrerit sapien cursus. Donec auctor ultricies ullamcorper. Donec dignissim semper massa vitae aliquet. Curabitur eu commodo mauris. Quisque vestibulum leo ex, id blandit magna venenatis ut. Fusce in sodales velit, in accumsan massa. Vivamus convallis lorem quis purus gravida molestie. In sed lectus fermentum, hendrerit metus et, lobortis libero. Curabitur porta, orci non consequat consectetur, libero lorem luctus dolor, a mattis massa mauris ut augue. Suspendisse potenti. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Etiam elit velit, cursus id felis ut, commodo elementum quam. Praesent ultricies purus ac augue commodo, a tincidunt est venenatis. Mauris ipsum massa, finibus sit amet lacus et, ornare volutpat massa. Nullam ex ipsum, egestas sed felis id, pharetra ultrices orci. Vivamus nisl purus, scelerisque ut quam eu, iaculis posuere massa. Proin vitae lectus efficitur, cursus mi non, molestie lacus. Pellentesque pulvinar vulputate orci eleifend eleifend. Maecenas fermentum purus at libero pharetra bibendum ac eu nulla. Vivamus porta pulvinar nisi sed ullamcorper. Mauris tincidunt fringilla risus, id sollicitudin est. Donec a mi turpis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque eget dictum eros. Donec laoreet auctor quam, nec suscipit eros suscipit viverra. Nunc eu neque eget tellus placerat lacinia. In porttitor orci nunc, nec convallis est suscipit in. Proin convallis vestibulum vulputate. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam rhoncus interdum purus sed gravida. Donec aliquet, neque eget convallis hendrerit, turpis diam maximus tellus, a aliquet justo neque vitae neque. In in leo ac arcu vestibulum iaculis. Phasellus consequat nibh ullamcorper, commodo sem eget, rhoncus risus. Phasellus interdum nulla scelerisque arcu aliquet, ac hendrerit sapien sollicitudin. Proin posuere ut ipsum quis vestibulum. Proin suscipit feugiat rutrum. Ut finibus nisi sem, ut aliquam dui tincidunt in. Proin in consectetur felis, ut porttitor tellus. Donec fermentum metus sit amet metus commodo, vitae sollicitudin leo commodo. Sed commodo cursus augue egestas eleifend. In nec enim ipsum. Maecenas suscipit ipsum at lacinia lacinia. Praesent varius consequat ante. Suspendisse sed vestibulum est, ut eleifend eros. Aenean ut lectus aliquet, ullamcorper lacus nec, congue eros. Proin dignissim finibus leo nec eleifend. Aenean ac massa vulputate, pharetra nulla eget, iaculis lectus. Proin luctus luctus ante. Fusce urna nunc, cursus et sem ut, cursus dapibus velit. Phasellus viverra eleifend ipsum ut lobortis. Curabitur in congue elit. In ornare viverra lorem, in elementum ligula porta sit amet. Suspendisse at mauris nibh. Nam eleifend orci ut dui pulvinar congue. Sed eu sem eu diam laoreet pulvinar. Nullam consectetur condimentum libero, vitae lacinia ipsum laoreet quis. Nullam vulputate tortor et turpis sollicitudin mollis. Nullam nulla justo, lobortis in nisi finibus, accumsan facilisis erat. Nullam sodales turpis sit amet nisi varius fermentum. Proin at consequat justo, sit amet varius lacus. Cras porttitor neque ipsum, ut pretium lorem volutpat vel. Vivamus dui turpis, tristique auctor quam eu, gravida cursus purus. Integer ullamcorper odio eget enim varius, quis volutpat mi commodo. Vestibulum urna ante, blandit eu diam eget, mollis faucibus arcu. Mauris finibus facilisis felis in condimentum. Vestibulum nisl ligula, venenatis id sem eu, tincidunt interdum tortor. Vivamus placerat faucibus dolor. Donec in lobortis lorem. Nam eu eros nec dui aliquet facilisis a a augue. Aenean tortor turpis, vehicula in tincidunt sed, pretium vitae quam. Ut ullamcorper porta ipsum, nec convallis velit scelerisque id. Donec congue finibus consectetur. Phasellus ut neque elit. Donec porta fermentum auctor. Vivamus imperdiet leo et magna vehicula vestibulum. Curabitur sed sollicitudin elit. Integer commodo ligula turpis, non sollicitudin arcu sodales id. Mauris est tellus, tempus vitae consectetur et, sodales nec ante. Mauris fringilla velit et purus dignissim, ut scelerisque leo porttitor. Mauris eleifend, sapien vel dignissim venenatis, tellus enim congue nulla, in porttitor ligula risus non lacus. Sed consequat ligula sed mauris placerat blandit. Quisque condimentum ante non eros gravida egestas. Nunc sit amet nisl eu purus efficitur semper in eu ante. Pellentesque elit enim, lobortis interdum rhoncus eget, semper ac dui. Aliquam diam lectus, vestibulum malesuada vehicula a, pretium eu sem. Vivamus vel varius odio. Morbi congue nunc vitae nibh ultricies pretium. Praesent felis velit, congue sed egestas eu, varius convallis lacus. Nam scelerisque purus sit amet orci luctus pellentesque. Duis ullamcorper sapien faucibus ante mollis, nec dapibus leo vulputate. Cras sed dignissim sem. Sed efficitur sem non odio efficitur dignissim. Mauris porta, justo at varius dapibus, nunc nibh porta ante, quis varius metus nunc vitae ligula. Nam ullamcorper non tortor ut efficitur. Praesent tincidunt ac sapien quis iaculis. Maecenas ullamcorper tortor vel interdum aliquam. Sed sit amet tempor nibh. Fusce ligula dolor, bibendum nec lacinia eu, interdum a est. Nulla sagittis metus ac leo tempus, eu lobortis eros gravida. Praesent sem felis, suscipit vitae ante ac, commodo finibus lorem. Nulla vestibulum nisi leo, a maximus velit ornare et. Nulla gravida placerat magna, nec imperdiet sem porta non. Mauris ut facilisis lectus, id condimentum nunc. Nulla felis nisi, malesuada nec pellentesque sed, finibus et sapien. Praesent gravida malesuada nisi. Praesent a elementum dui. Sed non massa vitae magna consectetur sagittis eu vel mauris. Nulla ut leo diam. Cras at tristique dolor, ut dignissim ipsum. In ac arcu malesuada, bibendum mi sit amet, facilisis turpis. Morbi dignissim semper bibendum. Vivamus dignissim molestie feugiat. Sed volutpat hendrerit metus, et tempor orci consequat ut. Sed accumsan dui urna, id sollicitudin quam venenatis ut. Quisque elementum, quam sit amet suscipit varius, purus leo dignissim turpis, at ultrices sem elit sed sapien. In tempor, sapien eget tincidunt tristique, mi felis elementum lorem, quis auctor enim lectus ac purus. Sed eu urna augue. Etiam malesuada hendrerit vestibulum. Phasellus quis lectus felis. Suspendisse quam metus, auctor laoreet lorem vitae, suscipit sollicitudin est. Etiam pharetra interdum sodales. Nulla facilisi. Nullam nec porta diam. Pellentesque massa magna, accumsan non dignissim non, sodales at risus. Maecenas magna purus, bibendum sit amet leo rhoncus, ornare vestibulum velit. Proin egestas facilisis arcu quis posuere. Integer euismod condimentum dui, at aliquam ligula dignissim sed. Curabitur lacinia dolor vel metus euismod, in pharetra nibh';
		$res = is_int($i) && $i < strlen($l) ? substr($l, 0, $i) : $l;
		$sep = is_array($multiple) && isset($multiple[1]) && (!is_array($multiple[1]) || !is_object($multiple[1])) ? $multiple[1] : '';
		$res0 = $res;
		if(is_int($multiple) || is_array($multiple))
			for ($ii = is_array($multiple) ? $multiple[0] - 1 : $multiple - 1; $ii > 0; $ii--) { 
				$res .= $sep . strtolower($res0);
			}
		if(is_callable($callable)){
			$res = $callable($res);
		}
		elseif($callable == 'word-letter'){
			$res = ucfirst(strtolower(str_replace([' ', ',', '.'], null, str_shuffle($res))));
		}
		elseif($callable == 'word'){
			$res = explode(' ', $l);
			$t = [];
			shuffle($res);
			for ($ii = 0; $ii < $i; $ii++) { 
				$t[] = $res[$ii];
			}
			$res = ucfirst(strtolower(trim(str_replace([',', '.'], null, implode(' ', $t)))));
		}
		return $res;
	}
}
// if (!function_exists('componentRender')) {
// 	function componentRender(string $path, array $datas = []){
// 		Component::viewRender($path, $datas);
// 	}
// }

if (!function_exists('id_generate')) {
	function id_generate(string|null $code) : string{
		$alphabet = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
		$code = is_null($code) ? null : strtoupper($code);
		if($code === null){
			$code = 'AA-00-AA-00';
		}
		else{
			$table = [($code[0]), ($code[1]), ((int) ($code[3] . $code[4])), ($code[6]), ($code[7]), ((int) ($code[9] . $code[10]))];
			$table_reverse = array_reverse($table);
			$last = null;
			$a = '';
			$code_format = [];
			foreach ($table_reverse as $key => $code_part) {
				if ($last == null || $last == 0 || $last == 'A') {
					$current = 0;
					if(is_int($code_part)){
						$current = $last === null || (isset($table_reverse[$key - 1]) && $table_reverse[$key - 1] != $last) ? (($code_part + 1) == 100 ? 0 : $code_part + 1) : $code_part;
					}
					else{
						$k = array_search(strtolower($code_part), $alphabet) + 1;
						$current = $last === null || (isset($table_reverse[$key - 1]) && $table_reverse[$key - 1] != $last) ? (isset($alphabet[$k]) ? strtoupper($alphabet[$k]) : strtoupper($alphabet[0])) : strtoupper($code_part);
					}
					$code_format[] = is_int($current) ? ($current <= 9 ? '0' . $current : $current) : $current; 
					$last = $current;
				} else {
					$code_format[] = is_int($code_part) ? ($code_part <= 9 ? '0' . $code_part : $code_part) : $code_part; 
					$last = $code_part;
				}	
			}
			$code_format = array_reverse($code_format);
			$code = $code_format[0] . $code_format[1] . '-' . $code_format[2] . '-' . $code_format[3] . $code_format[4] . '-' . $code_format[5];
		}
		return $code;
	}
	if(!function_exists('trims')){
		function trims(string $str, array|string|null $del = null, Closure|null $callable = null){
			$del = is_array($del) ? $del : [$del];
			$result = $str;
			foreach ($del as $value) {
				if(is_string($value) || is_numeric($value)) $result = trim($result, $value);
				elseif(is_null($value)) $result = trim($result);
				else{
					$result = false;
					break;
				}
			}
			if (is_callable($callable)) {
				$result = $callable($result);
			}
			return $result;
		}
	}
	if(!function_exists('currentRouteName')){
		function currentRouteName(){
			return Route::getCurrentRequest()->getName();
		}
	}
	if(!function_exists('apos')){
		function apos(string $str, string|bool $tagU = false, bool $change = false){
			return $change === false ? str_replace("'", ($tagU === false ? '&apos;' : ($tagU === true || empty(trim($tagU)) ? '%27' : trim($tagU))), $str) : str_replace(($tagU === false ? '&apos;' : ($tagU === true || empty(trim($tagU)) ? '%27' : trim($tagU))), "'", $str);
		}
	}
	if(!function_exists('array_remove')){
		function array_remove(mixed $element, array $array, bool|null $reinit_key = false, bool|null $is_pattern = false) : array {
			$k = array_search($element, $array);
			$res = $array;
			if($k !== false || $is_pattern === true){
				$GLOBALS['key_iU*(Wq2w@&*E@&?EFD@&?QSQUG?@']['k'] = $k;
				$GLOBALS['key_iU*(Wq2w@&*E@&?EFD@&?QSQUG?@']['val'] = $element;
				$GLOBALS['key_iU*(Wq2w@&*E@&?EFD@&?QSQUG?@']['pattern'] = $is_pattern;
				$res = array_filter($res, function($val, $key){
					$ver = $GLOBALS['key_iU*(Wq2w@&*E@&?EFD@&?QSQUG?@']['pattern'] === true && is_string($GLOBALS['key_iU*(Wq2w@&*E@&?EFD@&?QSQUG?@']['val']) ? !preg_match($GLOBALS['key_iU*(Wq2w@&*E@&?EFD@&?QSQUG?@']['val'], $val) :$GLOBALS['key_iU*(Wq2w@&*E@&?EFD@&?QSQUG?@']['k'] != $key;
					return $ver;
				}, ARRAY_FILTER_USE_BOTH);
				$res = $reinit_key === true ? array_values($res) : $res;
			}
			return $res;
			// var_dump(array_remove('aa', ['aa', 'bbl', 'suw'])); // with search element
			// var_dump(array_remove('/[a]/', ['aa', 'bbl', 'suw'], false, true)); // with search pattern
		}
	}
	
	// echo "<pre>";
		// $code = ['AA-00-AA-98', 'AA-00-AA-99', 'AA-00-AZ-98', 'AA-00-AZ-99', 'AA-00-ZZ-98', 'AA-00-ZZ-99', 'AA-99-ZZ-98', 'AA-99-ZZ-99', 'AZ-99-ZZ-98', 'AZ-99-ZZ-99', 'ZZ-99-ZZ-98', 'ZZ-99-ZZ-99'];
		// $co = 0;
		// for ($i = 0; $i < count($code); $i++) {
		// 	$c = id_generate($code[$i]);
		// 	echo "\n\n\t<span style=\"color:" . ($co <= 1 ? 'red' : 'blue') . ";\">" . (($i + 1) <= 9 ? ($i + 1) . ' ' : $i + 1) . ' : ' . $c . "</span>\n\n";
		// 	if($co == 3) $co = 0;
		// 	else $co++;
		// }
}
Helper::singleton();
