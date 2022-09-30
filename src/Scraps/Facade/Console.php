<?php
/**
 * 
 */
class Console extends Facade{
	
    protected static function getAccessor(){
        return 'Scraps\Consoles\Console';
    }
    protected static function singleton(){return true;}

}