<?php
namespace Scraps\Servers;
use Enter;
/**
 * 
 */
class Server{
	public $default_host = '127.0.0.1:80';

	public string|null $title;
	public string|null|array $more;
	public string|null $serverOs;

	public string $stringResponse;

	public function __construct($title = 'Server Consius', $more = null){
		$this->title = $title;
		$this->more = Enter::more($more);
	}

	public function __toString() : string{
		return !empty($this->stringResponse) ? $this->stringResponse : 'null'; 
	}

	protected static function getFactorySpace(){ return 'Core\Servers\Factories\\'; }

	public function init(){
		$os = self::getFactorySpace() . config('os-family');
		$pass = true;
		$server = true;
		// var_dump($this->more);
		$server_conf = self::server_config();
		if(!is_null($this->more)){
			if(isset($this->more['server'])) $server = $this->more['server'];
			if(isset($this->more['host'])){
				$server_conf['ip'] = $this->more['host'];
				$server_conf['generate_domain'] = false;
			}
			if(isset($this->more['domain'])){
				$do = $this->more['domain'];
				if(is_array($do)){
					$an = [];
					foreach ($do as $key => $v) {
						$an[]['name'] = $v;
					}
					$do = $an;
				}
				$do = is_string($do) ? [['name' => $do]] : $do;
				$server_conf['host'] = [
					[
						'domains' => $do
					],
				];
				$server_conf['generate_domain'] = true;
			}
			if(isset($this->more['generate-domain'])) $server_conf['generate_domain'] = $this->more['generate-domain'];
			// var_dump(isset($server_conf['generate_domain']) && $server_conf['generate_domain'] === true);
		}
		// exit();
		$doms = ['state' => false];
		if(isset($server_conf['generate_domain']) && $server_conf['generate_domain'] === true){
			if(is_array($server_conf['host']) || is_string($server_conf['host'])){
				$doms = $os::add_local_domain($server_conf['host'], $server_conf['ip'] && isset($server_conf['ip']) ? (is_array($server_conf['ip']) ? end($server_conf['ip']) : $server_conf['ip']) : null);
			}
			elseif ($server_conf['host'] === true && config('name')) {
				$doms = $os::add_local_domain(config('name'));
			}
			else{
				$doms['state'] = false;
				$doms['message'] = "Veuillez à bien configurer vos données du domaine de votre serveur dans votre fichier `config\app.php`." ;
			}

			echo "\n" . $doms['message'] . "\n";
			if($doms['state'] === false) $pass = false;
		}
		if($pass === true && $server === true){
			$ip = []; // $default_host
			if(!is_null($this->more) && isset($this->more['host'])){
				$ip = is_array($this->more['host']) ? $this->more['host'] : (is_string($this->more['host']) ? [['host' => $this->more['host']]] : [['host' => self::$default_host]]);
			}
			elseif($server_conf && is_array($server_conf) && (isset($server_conf['ip']) || (isset($server_conf['host']) && is_array(isset($server_conf['host'])) && isset(current($server_conf['host'])['ip']) ))){
				foreach ($server_conf['host'] as $key => $value){
					$ip[] = ['host' => isset($value['ip']) ? $value['ip'] : (!in_array($server_conf['ip'], $ip) ? $server_conf['ip'] : (!in_array(self::$default_host, $ip) ? $default_host : null)), 'domains' => isset($value['domains']) ? $value['domains'] : null];
				}
			}
			elseif(isset($doms['new'])){
				foreach ($doms['new'] as $key => $value) 
					$ip[] = ['host' => $value['ip']];
			}
			else{ $ip = [['host' => self::$default_host]]; }
			if(isset($this->more['external-server']) && $this->more['external-server'] === true){
				foreach ($os::ip() as $vv) {
					$ip[] = ['host' => $vv];
				}
			}
			foreach ($ip as $v) {
				$h = explode(':', $v['host']);
				$p = isset($h[1]) ? $h[1] : 80;
				$h = $h[0];
				if(!preg_match('/^127.0.0.0/', $h)){
					$d = isset($v['domains']) ? $v['domains'] : (isset($this->more['domain']) ? $this->more['domain'] : null);
					if(is_array($d)){
						$smm = [];
						foreach ($d as $val) {
							$smm[] = is_array($val) && isset($val['name']) ? (isset($val['sub']) ? $val['sub'] . '.' : null) . $val['name'] : (is_string($val) ? $val : null);
						}
						$d = $smm;
					}
					else $d = $d === null ? null : [$d];
					echo "\nIP : " . $h . " | DOMAINE : " . (is_null($d) ? 'NOTHING' : implode(', ', $d)) . "\n\n";
					$this->exec($h, $p, null, count($ip) > 1 ? true : (!is_null($this->more) && is_array($this->more) && isset($this->more['window']) && $this->more['window'] === true ? true : false), (!is_null($this->more) && is_array($this->more) && isset($this->more['title']) ? ((string) $this->more['title']) : null));
				}
				elseif(preg_match('/^127.0.0.0/', $h)) echo "\nIP : " . $h . " | ERREUR : CETTE ADRESSE NE PEUT PAS ETRE UTILISER 127.0.0.0\n";
				else echo "\nIP : " . $h . " | ERREUR : IP(HOST) INVALIDE OU UTILISE\n";
			}
			// exec('php -S '. $host . (is_null($port) ? null : ':' . $port) . ' -t ' . $root, $output);
		}
	}
	public static function url_exist(string $url){
		$url = preg_match('/^http:\/\/(.*?)/', $url) ? $url : (preg_match('/^ftp:\/\/\/(.*?)/', $url) ? $url : (preg_match('/^https:\/\/(.*?)/', $url) ? $url : 'http://' . $url));
		error_reporting(E_ALL & ~E_WARNING);
		$ret = get_headers($url);
		error_reporting(E_ALL);
		return $ret === false ? false : true;
	}
	public function exec(string $host, int|string|null $port = 80, string|null $root = null, bool $new_win = false, string|null $title = null) : bool{
		$root = is_null($root) ? ROOT . '\\public' : $root;
		// echo(($new_win === false ? null : ('start /D "' . dirname($root) . '" ' . (is_null($title) ? null : '"' . $title . '" '))) . 'php -S '. $host . (is_null($port) ? null : ':' . $port) . ' -t "' . $root . '"', $output, $state);
		exec(($new_win === false ? null : ('start /D "' . dirname($root) . '" ' . (is_null($title) ? null : '"' . $title . '" '))) . 'php -S '. $host . (is_null($port) ? null : ':' . $port) . ' -t "' . $root . '"', $output, $state);
		return $state === 0 ? true : false;
	}

	public static function server_config(string|null $k = null){
		$r = config('server');
		if(!is_null($r) && isset($r['host'])){
			$r['host'] = is_array($r['host']) ? $r['host'] : (is_string($r['host']) ? [$r['host']] : null);
			// sfc /? : pour verifier si la console est en tant que admin.
		}
		return !is_null($k) ? (isset($r[$k]) ? $r[$k] : false) : (is_null($r) ? false : $r);
	}

}




	// arrêt TASKKILL /F /FI "WINDOWTITLE eq Server Consius" /IM php.exe 
	// verif TASKLIST /FI "WINDOWTITLE eq Server Consius"