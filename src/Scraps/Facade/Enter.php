<?php
/**
 * 
 */
class Enter extends Facade{
	
    protected static function getAccessor(){
        return 'Scraps\Enters\Enter';
    }
    protected static function singleton(){return true;}
}