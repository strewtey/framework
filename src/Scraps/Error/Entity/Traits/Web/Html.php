<?php
namespace Scraps\Error\Entity\Traits\Web;
/**
 * 
 */
trait Html {

	private static function fatalHtml(){
		$content = [
			'1' => '<pre style="white-space:normal;font-family:\'Consolas\', \'Courier New\', arial, sans-serif;background-color: rgb(190, 0, 0);color:white;padding:20px;line-height:50px;font-size:25px;">@content@</pre>',
			'2' => '<div style="white-space:normal;padding:10px;"><div style="white-space: normal;background-color:rgba(200,0,0,.02);border-radius:25px;padding:35px;box-shadow:0 0 20px rgba(200,0,0,.2);color:rgb(240,0,0);font-family:\'Segoe UI\', arial, sans-serif;letter-spacing:3px;font-size:24px;line-height:50px;">@content@</div></div>'
		];
		// $div = [
		// 	'1' => '<pre>@div@</pre>',
		// 	'2' => '<div style="">@div@</div>'
		// ];
		switch (self::$style) {
			case 2:
				// var_dump(explode("\n", self::$message));
				$co = str_replace('@content@', self::$message, $content['2']);
				return $co;
				break;
			
			default:
				$co = str_replace('@content@', self::$message, $content['1']);
				return $co;
				break;
		}
	}

}