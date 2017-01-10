<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Value.php 07.04.2016 15:11:27
 */

/**
 * Description of file_name
 * 
 * @category   Hosh
 * @package    Hosh_Manager
 * @subpackage Acl
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Acl_Value
{
	/**
	 * @var string
	 */
	protected $id;

	const CLASSNAME = 'ACL_VALUE';
	
	/**
	 * @param string $id
	 */
	public function __construct($id){
		$this->id = $id;
	} 
	
	/**
	 * Get acl
	 * 
	 * @return array
	 */
	public function getAcl(){
		static $result;
		if (isset($result[$this->id])) {
		    return $result[$this->id];
		}
		require_once 'Hosh/Manager/Db/Package/Hosh/Acl/Value.php';
		$pack = new Hosh_Manager_Db_Package_Hosh_Acl_Value();
		$result[$this->id] = $pack->getAcl($this->id);
		return $result[$this->id];
	}
	
	/**
	 * Get users
	 * @return array
	 */
	public function getAclUser(){
	    static $result;
	    if (isset($result[$this->id])) {
	        return $result[$this->id];
	    }
	   
	    $pack = new Hosh_Manager_Acl_User();
	    $adapter = $pack->getAdapter();
	    $result[$this->id] = $adapter->getList(array('idvalue'=>$this->id));	    
	    return $result[$this->id];
	}
}