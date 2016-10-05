<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: AdapterAbstract.php 07.04.2016 11:18:10
 */

/**
 * Abstract class Role
 * 
 * @category   Hosh
 * @package    Hosh_Manager
 * @subpackage Acl
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
abstract class Hosh_Manager_Acl_Role_AdapterAbstract
{
	/**
	 * @return array
	 */
	abstract public function getList();
				
	/**
	 * @param string $id
	 * @return array
	 */
	abstract public function getObject($id);	
	
}