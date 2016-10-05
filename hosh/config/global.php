<?php
return array(   
            'version' => '1.0.1',
                 
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
            'path' => __DIR__.'/../',
            'path_public' => __DIR__.'/../../public/assets/',
            'path_libraries' => __DIR__.'/../../vendor/',
            
            
            
            // url paths
            'url_path' => '/',
            'url_public' => '/assets',
            
            'log' => false,
            
            // form
            'form' => array(
                    'path_patternxml' => __DIR__ . '/../extensions/form/xml/',
             ),
    );