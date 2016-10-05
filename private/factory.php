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
	    require_once dirName(__FILE__).'/configuration.php';
	    $hoshconfig = new HoshConfiguration();
	    $config_array = $hoshconfig->get();
	    $config_array = array_merge($config_array,$config);
	    require_once $config_array['path_libraries'].'Hosh/Factory.php';
	    $hosh_factory = new Hosh_Factory();
	    $hosh_factory->init($config_array);
	}
}