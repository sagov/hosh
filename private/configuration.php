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
	 * @var array
	 */
	protected $config = array(
							'db_default' => array(
									'adapter' => 'pdo_mysql',
									'params'  => array(
											'host'     => 'localhost',
											'username' => 'root',
											'password' => '',
											'dbname'   => 'sfwdb',
											'charset' => 'utf8',
											'preff' => 'preff_',
									),
							
							),
	                        'log' => true,
	                        // path directories
	                        'path'=> '',
							'path_public'=>'',
	                        'path_libraries'=>'',	                        	        
	                        // url paths
	                        'url_path'=> '/manager.php',
	                        'url_public'=>'/public',	                        
	                        
	        
	                        'adapter'=>'Default',
						);
	
	
	/**
	 * Get Configuration
	 * @return array
	 */
	public function get()
	{
	    
	    $this->config['path'] = dirName(__FILE__);
	    $this->config['path_public'] = dirName(__FILE__).'../../public/';
	    $this->config['path_libraries'] = dirName(__FILE__).'/libraries/';
	    
		return $this->config;
	}
}