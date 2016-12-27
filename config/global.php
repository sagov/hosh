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
                        'dbname' => 'hoshdb',
                        'charset' => 'utf8',
                        'preff' => 'preff_'
                ) 
        ),
        
        // path directories
        'path' => __DIR__ . '/../hosh/',
        'route' => '/',
        
        'vendor' => array(
                'path' => __DIR__ . '/../vendor/Hosh/'
        ),
        
        'public' => array(
                'path' => __DIR__ . '/../public/assets/',
                'url' => '/assets'
        ),
        
        'log' => true,
        
        // form
        'form' => array(
                'plugin' => array(
                        'path' => __DIR__.'/../hosh/plugins/form/'
                ),
                'pattern' => array(
                        'path_xml' => __DIR__.'/../hosh/extensions/form/xml/'
                )
        )
        
);