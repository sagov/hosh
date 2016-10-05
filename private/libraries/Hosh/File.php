<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_File
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: File.php 21.04.2016 17:53:12
 */

/**
 * File
 *
 * @category Hosh
 * @package Hosh_File
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_File
{

    /**
     * Preff File
     * @param string $file
     * @return string
     */
    public function getFilePreff ($file)
    {
        $config = Hosh_Config::getInstance();
        $file_path = $config->get('path_public') . $file;
        if (file_exists($file_path)) {
            return $file . '?' . filemtime($file_path);
        }
        return $file;
    }
}