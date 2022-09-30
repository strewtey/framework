<?php 
namespace Support;
/**
 * 
 */
class Image extends Support
{
	protected static $instance;
	protected static $aliases = [];
	public function _currentColor(string $image, $pos = null, $calc = 0, $opacity = 1)
	{
		if(empty($pos)){
			$size = getimagesize($image);
			$pos = array(($size[0] / 2), ($size[1] / 2));
		}
		$image = imagecreatefromjpeg($image);
		$rgb = imagecolorat($image, $pos[0], $pos[1]);
		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >> 8) & 0xFF;
		$b = $rgb & 0xFF;
		return 'rgba('.($r + ($calc)).','.($g + ($calc)).','.($b + ($calc)).', ' . $opacity . ')';
		// var_dump(file_exists($image));
	}
}