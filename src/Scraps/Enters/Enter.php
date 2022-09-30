<?php
namespace Scraps\Enters;
use Scraps\Factory\Factory;
use Console;
/**
 * 
 */
class Enter extends Factory{

	private static $named;
	private static $commands;
	private static $commandRoute;
	private $action;
	private $params = [];
	
	public function __construct($file = ROOT . '/routes/consoles.php'){
		if(is_null(self::$commandRoute)) self::$commandRoute = $GLOBALS['argv'];
		if(is_null(self::$commands)){
			$f = trim(__DIR__, '\\') . '\routes.php';

			if(file_exists($file)) require_once $file;

			if(file_exists($f)) require_once $f;
		}
	}

	public static function getCommandRoute(bool $str = true) : string|array|null {
		return 
			!is_null(self::$commandRoute) && !empty(self::$commandRoute)
				? (
					$str === true
						? 'php ' . implode(' ', self::$commandRoute)
						: self::$commandRoute
				)
				: null
		;
	}

	public function route(string|array $line, string|callable|array $action){
		self::$commands[] = ['line' => is_array($line) ? $line : [$line], 'action' => $action];
		return $this;
	}
	public function run(){
		array_shift($GLOBALS['argv']);
		foreach (self::$commands as $command) {
			if(isset($GLOBALS['argv']) && $c = implode(' ', $GLOBALS['argv'])){
				if ($res = $this->match($command, trim($c))) {
					$this->execute();
					break;
				}
			}
			// $c = Console::readline();
			// if(is_null(self::$firstCommand)){
			// }
			// var_dump($c);
			// if(!empty)
		}
	}
	public function match(array $command, string $commandLine){
		if(in_array($commandLine, $command['line'])) {
			$this->action = $command['action'];
			return true;
		}
		$commandLine = str_replace('}', '&##!!', str_replace('{', '!!##&', $commandLine));
		foreach ($command['line'] as $value) {
			preg_match_all('/{(.*?)}/', $value, $args); array_shift($args);

			$pattern = '/^' . preg_replace('/{([a-z0-9_\/]+\?)}/', '(.*)', $value) . '$/i';
			$pattern = preg_replace('/{(.*?)}/', '([a-z0-9_\/]+)', $pattern);
			preg_match($pattern, $commandLine, $val); array_shift($val);
			if(!empty($val) && is_array($val)){
				$datas = [];
				foreach ($args[0] as $k => $v){
					$datas[str_replace('?', '', $v)] = $val[$k];
				}
				$this->action = $command['action'];
				$this->params = $datas;
				return true;
			}
		}
		return false;
	}
	public function execute(){
		if(!is_null($this->action)){
			if(is_callable($this->action))
				return call_user_func($this->action, ...$this->params);
			$action = is_array($this->action) ? $this->action : explode('@', $this->action);
			if(method_exists($action[0], $action[1])){
				$cons = isset($action[2]) && ($action[2] === true || $action[2] == 'const' || $action[2] == 'construct');
				$controller = $cons === true && !empty($this->params) ? new $action[0](...$this->params) : new $action[0]();
				$method = $action[1];

				if(!empty($this->params)) return $cons === false ? $controller->$method(...$this->params) : $controller->$method();
				else return $controller->$method();
			}
			else die('Controller Class not exists : ' . $action[0] . '::' .$action[1]);
		}
	}
	// public static function more(string|null $more, array $pattern = ['default' => '/--([a-z0-9&;_:#.\-]+=\'[a-z0-9&;_:#.\-\s]+\')|--(\'[a-z0-9&;_:#.\-\s]+\'=[a-z0-9&;_:#.\-]+)|--(\'[a-z0-9&;_:#.\-\s]+\'=\'[a-z0-9&;_:#.\-\s]+\')|--([a-z0-9&;_:#.\-]+=[a-z0-9&;_:#.\-]+)|--(\'[a-z0-9&;_:#.\-\s]+\')|--([a-z0-9&;_:#.\-]+)/i', 'type' => '/\'(.*?)\'/']) : array|null {
	public static function more(string|null $more, bool|null|string $be_null = false, callable|null|array $Closure = null, array $pattern = ['default' => '/--([^\s]+=\'[^=]+\')|--(\'[^=]+\'=[^\s]+)|--(\'[^=]+\'=\'[^=]+\')|--([^\s]+=[^\s]+)|--(\'[^=]+\')|--([^\s]+)/i', 'type' => '/\'(.*?)\'/']) : array|null {
		$Closure = is_callable($Closure) ? ['return' => $Closure] : (is_array($Closure) && ((isset($Closure['items']) && is_callable($Closure['items'])) || (isset($Closure['return']) && is_callable($Closure['return']))) ? $Closure : null);
		$be_null = $be_null === null ? false : $be_null;
		if($more === null) return null;
		if (!function_exists('type_verif_e9u2e2')) {
			function type_verif_e9u2e2(string $pattern, string $value, bool|string|null $be_null){
				return preg_match($pattern, $value)
					? trim($value, "'")
					: (
						$value == 'true'
							? true
							: (
								$value == 'false'
								? false
								: (
									$value == 'null'
									? ($be_null === false ? null : (is_string($be_null) && !empty($be_null) ? $be_null : '!beNull'))
									: (
										((float) $value) && preg_match('/[0-9]+/', $value) && !(preg_match('/\.([0-9]+)\./', $value))
										? ((float) $value)
										: trim($value, "'")
									)
								)
							)
					)
				;
			}
		}
		$m = [];
		$more = str_replace("\'", '&apos;', $more);
		$more = str_replace("\:", '&dbp;', $more);
		preg_match_all($pattern['default'], $more, $matches);
		if(!empty($matches[1]) || !empty($matches[2]) || !empty($matches[3]) || !empty($matches[4]) || !empty($matches[5]) || !empty($matches[6])){
			array_shift($matches);
			foreach ($matches as $vd) {
				foreach ($vd as $v) {
					if(!empty($v)){
						$a = explode('=', $v);
						$val = null;
						if(isset($a[1])){
							$val = explode(':', $a[1]);
							if(count($val) > 1){
								$vl = [];
								foreach ($val as $oov) {
									$db = type_verif_e9u2e2(isset($pattern['type']) ? $pattern['type'] : '/\'(.*?)\'/', trim($oov, "'"), $be_null);
									$db = is_string($db) ? (((int) str_replace('#', null, $db)) ? str_replace('#', null, $db) : str_replace('\#', '&hastag;', $db)) : $db;
									$db = !is_string($db) ? $db : str_replace('\#', '&hastag;', $db);
									$vl[] = $db;
								}
								$val = $vl;
							}
							else{
								$val = type_verif_e9u2e2(isset($pattern['type']) ? $pattern['type'] : '/\'(.*?)\'/', $val[0], $be_null);
								$val = is_string($val) ? str_replace('&apos;', "'", $val) : $val;
								$val = is_string($val) ? str_replace('&dbp;', ":", $val) : $val;
							}
						}
						if(is_array($Closure) && isset($Closure['items']) && is_callable($Closure['items'])){
							$rr = $Closure['items']($a[0], $val);
							if(is_array($rr) && count($rr) == 2 && isset($rr['key']) && (isset($rr['value']) || $rr['value'] == null))
								$m[$rr['key']] = $rr['value'];
						}
						else $m[$a[0]] = $val;
						// echo "\n" . $v . "\n";
					}
				}
			}
		}
		if(!empty($m) && is_array($Closure) && isset($Closure['return']) && is_callable($Closure['return']))
			return $Closure['return']($m);
		else return empty($m) ? null : $m;
	}
	protected function getFactorySpace(){ return 'Scraps\Enters\Factories\\'; }

	// public function name($name){
	// 	if(isset(self::$named[$name])){
	// 		die("Name already exists !");
	// 	}
	// 	else self::$named[$name] = url($this->command);
	// }
}
// \Enter::more($more, 'kswhd', ['items' => function($k, $val){return ['key' => $k, 'value' => $val];}])