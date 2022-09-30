<?php
/**
 * 
 */
class Collect extends Facade{
	
    protected static function getAccessor(){
        return 'Scraps\Collects\Collect';
    }
    protected static function singleton(){return true;}

}