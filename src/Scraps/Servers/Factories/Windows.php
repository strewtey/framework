<?php
namespace Scraps\Servers\Factories;

/**
 * 
 */
abstract class Windows {

	private static $comment = "# Copyright (c) 1993-2009 Microsoft Corp.
#
# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
#
# This file contains the mappings of IP addresses to host names. Each
# entry should be kept on an individual line. The IP address should
# be placed in the first column followed by the corresponding host name.
# The IP address and the host name should be separated by at least one
# space.
#
# Additionally, comments (such as these) may be inserted on individual
# lines or following the machine name denoted by a '#' symbol.
#
# For example:
#
#      102.54.94.97     rhino.acme.com          # source server
#       38.25.63.10     x.acme.com              # x client host

# localhost name resolution is handled within DNS itself.
#	127.0.0.1       localhost
#	::1             localhost


";
	private static string $local_domain_file = 'C:\Windows\System32\drivers\etc\hosts';
	private static string $default_ip = '127.0.0.1';
	private static string $default_domain_name_suffix = 'com';
	// public static ;

	public static function add_local_domain(array|string $domains, string|null $ip = null, string|null $host_path = null) {
		self::$local_domain_file = $host_path !== null ? $host_path : (config('server') && isset(config('server')['host_path']) ? config('server')['host_path'] : self::$local_domain_file);
		if(file_exists(self::$local_domain_file)){
			$do = "";
			if(self::is_admin()){
				// var_dump();
				$all = self::all_domain($domains, $ip);
				foreach ($all['all'] as $key => $domain) {
					$do .= "\t" . $domain['ip'] . "       " . self::domain_name($domain) . "\n";
				}
				// $cnt = str_replace(self::$comment, null, file_get_contents(self::$local_domain_file));
				$r = ['state' => file_put_contents(self::$local_domain_file, self::$comment . $do) === false ? false : true, 'message' => "L'ajout a été un succès !", 'new' => $all['new']];
				if($r['state'] === false){
					$r['message'] = "Vous n'avez pas les droits necessaire pour configurer un nom de domaine local. Pour le faire veuillez changer le niveau de sécurité d'écriture de ce fichier `" . self::$local_domain_file . "` et réessayez !";
				}
				return $r;
			}
			else{
				$m = "\nGénérer un nom de domaine locale nécessite un niveau d'accès plus élévé. Veuillez donc executer votre console en tant que adminisatrateur\n";
				return ['state' => file_put_contents(self::$local_domain_file, self::$comment . $do) === false ? false : true, 'message' => $m];
			}
		}
		else{
			return ['state' => false, 'message' => "Le fichier de configuration de vos noms de domaine local est manquant dans votre système. L'emplacement du fichier par défaut pour `Windows 10` ou les versions interieur se trouve dans `" . self::$local_domain_file . "`. Si c'est un problème lier à votre système, veuillez le corriger et si l'emplacement n'est plus la même pour `Win 11` ou plus, veuillez trouver le nouveau emplacement et configurer le comme ceci `add_local_domain(...$domains, ...$ip, $emplacement_du_fichier_host)`"];
		}
		// return true;
	}
	private static function all_domain(array|string $domains, string|null $ip = null) : array|bool {
		$ip = is_null($ip) ? self::$default_ip : explode(':', $ip)[0];
		function verif_string_domain_wu9121($domains, $ip, $default_domain_name_suffix){
			if(is_string($domains)){
				$a = explode('.', $domains);
				if(count($a) >= 3){
					$domains = [['ip' => $ip, 'domains' => [['sub' => array_shift($a), 'name' => strtolower(implode('.', $a))]]]];
				}
				elseif(count($a) == 2) $domains = ip[['ip' => $ip, 'domains' => [['sub' => null, 'name' => strtolower(implode('.', $a))]]]];
				else $domains = [['ip' => $ip, 'domains' => [['sub' => null, 'name' => strtolower(implode('.', $a)) . '.' . $default_domain_name_suffix]]]];
			}
			return $domains;
		}
		$domains = verif_string_domain_wu9121($domains, $ip, self::$default_domain_name_suffix);
		// var_dump($domains);
		$mer = null;
		$local_domain = self::local_domain();
		foreach ($domains as $val) {
			$das = [];
			if(is_array($val['domains'])){
				foreach ($val['domains'] as $domain) {
					if(is_array($domain)){
						if(!isset($domain['sub'])){
							$domain['sub'] = null;
							asort($domain);
						}
						$das[] = ['ip' => isset($val['ip']) ? explode(':', $val['ip'])[0] : $ip, 'domain' => $domain];
					}
					else $das[] = verif_string_domain_wu9121($domain, isset($val['ip']) ? explode(':', $val['ip'])[0] : $ip, self::$default_domain_name_suffix)[0];
				}
			}
			else $das = verif_string_domain_wu9121($val['domains'], isset($val['ip']) ? explode(':', $val['ip'])[0] : $ip, self::$default_domain_name_suffix);
			$mer = is_null($mer) ? $das : array_merge($mer, $das);
		}
		$a = 0;
		$rrrr = [];
		foreach ($local_domain as $k => $local) {
			$a = null;
			foreach ($mer as $new) if(self::domain_name($new) == self::domain_name($local)) $a = true;
			if(is_null($a)) $rrrr[] = $local;
		}
		// priorité i don't know mais je met d'abord les ancien puis les nouveaux
		return ['new' => $mer, 'all' => array_merge($rrrr, $mer)];
	}

	public static function domain_name(array $tab){
		return isset($tab['domain']) && isset($tab['domain']['name']) ? (isset($tab['domain']['sub']) ? $tab['domain']['sub'] . '.' : null) . $tab['domain']['name'] : false;
	}

	public static function domain_exist(string $domain, bool $strict = true) : array|bool {
		$domain = strtolower($domain);
		$pattern = $strict === true ? '/^' . $domain . '$/' : '/' . $domain . '/';
		$rec = [];
		foreach (self::local_domain() as $value) {
			$str_domain = strtolower((is_null($value['domain']['sub']) ? null : $value['domain']['sub'] . '.') .  $value['domain']['name']);
			if(preg_match($pattern, $str_domain)){
				$rec[] = $value;
			}
		}
		return empty($rec) ? false : $rec;
	}
	public static function local_domain(){
		$cnt = str_replace(self::$comment, null, file_get_contents(self::$local_domain_file));
		preg_match_all('/\t([0-9.]+\s\s\s\s\s\s\s[a-z\-0-9.]+)/', $cnt, $matches);
		array_shift($matches); $matches = $matches[0];
		$m = [];
		foreach ($matches as $value) {
			$a = explode('%', str_replace('       ', '%', $value));
			$d = explode('.', $a[1]);
			$sub = count($d) >= 3 ? array_shift($d) : null;
			$m[] = ['ip' => $a[0], 'domain' => ['sub' => $sub, 'name' => implode('.', $d)]];
		}
		return $m;
	}
	public static function is_admin(bool $ret = false) : array|bool{
		exec('WHOAMI /PRIV /NH', $out, $st); $out = implode("\n", $out);
		$a = 3;
		if($st === 0){
			$ver = ['SeIncreaseQuotaPrivilege', 'SeSecurityPrivilege', 'SeTakeOwnershipPrivilege', 'SeLoadDriverPrivilege', 'SeSystemProfilePrivilege', 'SeSystemtimePrivilege', 'SeProfileSingleProcessPrivilege', 'SeIncreaseBasePriorityPrivilege', 'SeCreatePagefilePrivilege', 'SeBackupPrivilege', 'SeRestorePrivilege', 'SeDebugPrivilege', 'SeSystemEnvironmentPrivilege', 'SeRemoteShutdownPrivilege', 'SeManageVolumePrivilege', 'SeImpersonatePrivilege', 'SeCreateGlobalPrivilege', 'SeCreateSymbolicLinkPrivilege', 'SeDelegateSessionUserImpersonatePrivilege'];
			foreach ($ver as $value) {
				if(preg_match('/' . $value . '/i', $out)) $a--;
				if($a == 0) break;
			}
		}
		return $ret === true ? ['state' => $a <= 0, 'message' => ($a <= 0 ? 'Console en tant que adminisatrateur' : 'Console en tant qu\'invité')] : $a <= 0;
	}
	public static function ip() : array|bool|string {
		exec('ipconfig', $ipconfig);
		preg_match_all('/[a-z]+ IPv4[.\s]+: ([0-9.]+)/i', implode(' ', $ipconfig), $matches);
		array_shift($matches);
		return isset($matches[0]) && !empty($matches[0]) ? $matches[0] : false;
	}
}
