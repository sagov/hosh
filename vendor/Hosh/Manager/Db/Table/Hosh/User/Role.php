<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Role.php 08.04.2016 11:03:34
 */
require_once 'Hosh/Manager/Db/Table/Abstract.php';
/**
 * Table hosh_user_role
 *
 * @category   Hosh
 * @package    Hosh_Manager
 * @subpackage  Db
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh
 *
 */
class Hosh_Manager_Db_Table_Hosh_User_Role extends Hosh_Manager_Db_Table_Abstract
{
	/**
	 * @var unknown
	 */
	protected $_name = 'hosh_user_role';
}