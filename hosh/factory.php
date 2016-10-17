<?php
/**
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */
class HoshFactory
{		
	public static function run($config = array())
	{
	    static $result;
	    if (isset($result)){
	        return $result;
	    }
	    $result = true;
	    $config_array = require_once dirName(__FILE__).'/../config/global.php'; 
	    $config_array = array_merge($config_array,$config);
	    
	    require_once $config_array['vendor']['path'].'Factory.php';
	    $hosh_factory = new Hosh_Factory();
	    $hosh_factory->init($config_array);
	}
}