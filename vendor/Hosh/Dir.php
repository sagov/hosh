<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Dir
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Dir.php 21.04.2016 17:42:50
 */

/**
 * Dir
 * 
 * @category   Hosh
 * @package     Hosh_Dir
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Dir
{

    /**
     * Scan Dir
     * @param string $path
     * @param array $filter
     * @throws Zend_Form_Exception
     * @return array 
     */
    public function getListScan ($path, $filter = null)
    {
        if (! is_dir($path)) {
            return false;
        }
        $list = scandir($path);
        $result = array();
        foreach ($list as $val) {
            $flag = true;
            if (isset($filter['ex_name'])) {
                if (is_array($filter['ex_name'])) {
                    if (in_array($val, $filter['ex_name'])) {
                        $flag = false;
                    }
                } else {
                    if ($filter['ex_name'] == $val) {
                        $flag = false;
                    }
                }
            }
            
            if (isset($filter['isdir'])) {
                if ($filter['isdir']) {
                    if (! is_dir($path . '/' . $val)) {
                        $flag = false;
                    }
                } else {
                    if (is_dir($path . '/' . $val)) {
                        $flag = false;
                    }
                }
            }
            
            if (isset($filter['ext'])) {
                if (is_array($filter['ext'])) {
                    foreach ($filter['ext'] as $valext) {
                        if (strrpos($valext, $val) != 0) {
                            $flag = false;
                        }
                    }
                } else {
                    if (strrpos($filter['ext'], $val) != 0) {
                        $flag = false;
                    }
                }
            }
            
            if ($flag) {
                $result[] = $val;
            }
        }
        return $result;
    }
}