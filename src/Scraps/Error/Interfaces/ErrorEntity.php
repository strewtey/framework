<?php
namespace Scraps\Error\Interfaces {

	interface ErrorEntity{

		// public static function init() : string|array|bool|null;
		
		const ___INTERFACES = ['web' => ['apache2handler'],'console' => ['cli']];

		const ___ERROR_TYPE_LIST = ['fatal', 'warning'];

		public static function init(string $message);

		public static function fatal() : string;

		public static function warning() : string;

		// public static function getInterface();

	}
	
}