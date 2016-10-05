<?php

require_once 'Zend/Db.php';

class Hosh_Db extends Zend_Db
{
	protected static $_namespace = 'db_default';
	
	
	
	/**
	 * @param string $namespace
	 * @return Zend_Db_Adapter_Abstract
	 */
	public static function get( $namespace = null )
	{
		static $result;
		
		if (!isset($namespace)) {
		    $namespace = self::$_namespace;
		}
		
		if (isset($result[$namespace])){
		    return $result[$namespace];
		}
		$config = Hosh_Config::getInstance();
		$result[$namespace] = self::factory($config->get($namespace));
		$result[$namespace]->getConnection();
		return $result[$namespace];
	}
}