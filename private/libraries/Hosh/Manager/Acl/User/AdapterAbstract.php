<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: AdapterAbstract.php 07.04.2016 15:03:17
 */

/**
 * Abstract class Acl User Adapter
 * 
 * @category   Hosh
 * @package     Hosh_Manager
 * @subpackage  Acl
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
abstract class Hosh_Manager_Acl_User_AdapterAbstract
{
	
	/**
	 * @param array $filter
	 * @return array
	 */
	abstract public function getList($filter);		
}