<?php

class Hosh_Cache extends Zend_Cache
{

    protected static $_memcache;

    public static function getMemcache ()
    {
        if (isset(self::$_memcache)) {
            return self::$_memcache;
        }
        
        $options = array(
                'frontend' => array(
                        'caching' => true,
                        'lifetime' => 3600,
                        'automatic_serialization' => true
                ),
                'backend' => array(
                        'servers' => array(
                                array(
                                        'host' => '127.0.0.1',
                                        'port' => 11211
                                )
                        ),
                        'compression' => false
                )
        );
        
        self::$_memcache = Zend_Cache::factory('Core', 'Memcached', 
                $options['frontend'], $options['backend']);        
        return self::$_memcache;
    }
    
    /**
     * @param mixed $param
     * @return string
     */
    public static function getId($param)
    {
        return md5(Zend_Json::encode($param));
    }
}