<?php

/**
 * Configuration Hosh
 * 
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */
class HoshConfig
{   

    /**
     * Get Configuration
     * 
     * 
     *
     * @return array
     */
    public function get ()
    {
        return require_once dirName(__FILE__).'/config/global.php';        
    }
}