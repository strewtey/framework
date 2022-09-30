<?php
/**
 * 
 */
class Crypt extends Facade{
	
    protected static function getAccessor(){
        return 'Scraps\Crypt\Crypt';
    }
    protected static function singleton(){return false;}
}