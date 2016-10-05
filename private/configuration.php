<?php

/**
 * Configuration Hosh
 * 
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */
class HoshConfiguration
{

    /**
     *
     * @var array
     */
    protected $config = array(            
            'adapter' => 'Default',
            
            // Db
            'db_default' => array(
                    'adapter' => 'pdo_mysql',
                    'params' => array(
                            'host' => 'localhost',
                            'username' => 'root',
                            'password' => '',
                            'dbname' => 'sfwdb',
                            'charset' => 'utf8',
                            'preff' => 'preff_'
                    )
            ),
            
            // path directories
            'path' => '',
            'path_public' => '',
            'path_libraries' => '',
            
            // url paths
            'url_path' => '/',
            'url_public' => '/public',
            
            'log' => false,
    );

    /**
     * Get Configuration
     *
     * @return array
     */
    public function get ()
    {
        $this->config['path'] = dirName(__FILE__);
        $this->config['path_public'] = dirName(__FILE__) . '../../public/';
        $this->config['path_libraries'] = dirName(__FILE__) . '/libraries/';
        
        return $this->config;
    }
}