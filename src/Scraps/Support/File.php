<?php 
namespace Support;
/**
 * 
 */
class File extends Support
{
	protected static $instance;
	public $path;
	protected static $aliases = [
		'extension' => '_extractExtension', 'ext' => '_extractExtension',
		'mime' => '_getMime',
		'info' => '_info',
	];
	public function _info(string $file){
		$f = pathinfo($file);
		$f['filepath'] = $file;
		$f['filesize'] = filesize($file);
		$f['mimetype'] = is_dir($file) ? 'directory' : File::mime($file);
		return $f;
	}
	public function _extractExtension(string $str, bool $all = null){
		$strR = explode('.', $str);
		$lst = strtolower(array_pop($strR));
		if($all === true)
			$lst = (object) ['ext' => $lst, 'name' => implode('.', $strR), 'file' => $str];
		return $lst;
	}
	// public static function extractExtension($str, $all = null){
	// 	$r = new File();
	// 	return $r->_extractExtension($str, $all);
	// }
	public function _lorem($r){
		$this->cResult();
	}
	public function _getMime($file){
		$mimeType = require __DIR__ . '\More\mime.php';
		$ext = $this->extension($file);
		// Remove a single leading dot
		// if (substr($ext, 0, 1) === '.') {
		// 	$ext = substr($ext, 1);
		// }
		$index = false;
		foreach($mimeType as $contentType => $extensions) {
			$index = array_search($ext, $extensions, true);
			if($index !== false) {
				return $contentType;
			}
		}
		error_reporting(E_ALL & ~E_WARNING);
		if($index === false){
			$m = mime_content_type($file);
			return is_dir($file) ? 'directory' : ($m == false ? 'application/x-' . $ext : $m);
		}
		error_reporting(E_ALL);

		return null;
	}
}