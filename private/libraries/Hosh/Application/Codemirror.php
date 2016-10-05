<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Application
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Codemirror.php 21.04.2016 17:57:06
 */

/**
 * Application List
 *
 * @category Hosh
 * @package Hosh_Application
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Application_Codemirror
{

    protected $_lib_mode = array(
            'sql' => array(
                    'text/x-plsql',
                    'text/x-sql',
                    'text/x-mysql',
                    'text/x-mariadb',
                    'text/x-cassandra',
                    'text/x-mssql',
                    'text/x-hive',
                    'text/x-pgsql',
                    'text/x-gql'
            ),
            'javascript' => array(
                    'text/javascript',
                    'application/json',
                    'application/ld+json',
                    'text/typescript',
                    'application/typescript'
            ),
            'css' => array(
                    'text/css',
                    'text/x-scss',
                    'text/x-less'
            ),
            'xml' => array(
                    'application/xml',
                    'text/html'
            )
    );

    public function getLibMode ()
    {
        return $this->_lib_mode;
    }
}