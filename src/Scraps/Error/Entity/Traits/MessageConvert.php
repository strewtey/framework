<?php 
namespace Scraps\Error\Entity\Traits;
/**
 * 
 */
trait MessageConvert {

	private static function messageConvert($message, $trace, $brReplace) : string|bool{
		$file = str_replace('@file@', $trace['file'], $message);
		$line = str_replace('@line@', $trace['line'], $file);
		return str_replace('@br@', $brReplace, $line);
	}
}