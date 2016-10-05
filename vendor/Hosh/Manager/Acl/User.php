<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: User.php 07.04.2016 15:09:54
 */

/**
 * Description of file_name
 * 
 * @category   Hosh
 * @package     Hosh_Manager
 * @subpackage  Acl_User
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Acl_User
{
	
	protected $_adapter = 'Default';
	
	/**
	 * @param string $adapter
	 * @return Hosh_Manager_Acl_User_AdapterAbstract
	 */
	public function getAdapter($adapter = null)
	{
		static $result;
	
		if (isset($result)) {
		    return $result;
		}
	
		if (empty($adapter)) {
            $config = Hosh_Config::getInstance();
            $adapter = $config->get('adapter',$this->_adapter);            
        }
        
	
		$adapterName = 'Hosh_Manager_Acl_User_Adapter_';
		$adapterName .= str_replace(' ', '_', ucwords(str_replace('_', ' ', strtolower($adapter))));
	
	
		if (!class_exists($adapterName)) {
			require_once 'Zend/Loader.php';
			Zend_Loader::loadClass($adapterName);
		}
		$result = new $adapterName();
		if (! $result instanceof Hosh_Manager_Acl_User_AdapterAbstract) {
			require_once 'Zend/Db/Exception.php';
			throw new Zend_Db_Exception("Adapter class '$adapterName' does not extend Hosh_Manager_Acl_User_AdapterAbstract");
		}
	
		return $result;
	}
}