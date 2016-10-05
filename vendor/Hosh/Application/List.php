<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Application
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: List.php 21.04.2016 17:57:06
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
class Hosh_Application_List
{

    /**
     *
     * @var unknown
     */
    protected static $_instance = null;

    /**
     *
     * @return Hosh_Application_List
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     *
     * @param array $list            
     * @return array
     */
    public function toTree ($list)
    {
        $result_select = array();
        foreach ($list as $val) {
            if (empty($val['idparent'])) {
                $val['idparent'] = null;
            }
            $result_select[$val['idparent']][] = $val;
        }
        $result_select = $this->_build_tree($result_select, null);
        return $result_select;
    }

    /**
     *
     * @param array $list            
     * @param string $parent_id            
     * @param array $tree            
     * @param number $ncount            
     * @return mixed
     */
    protected function _build_tree ($list, $parent_id, & $tree = array(), $ncount = 0)
    {
        if (is_array($list) and isset($list[$parent_id])) {
            if (count($tree) > 0) {
                ++ $ncount;
            }
            foreach ($list[$parent_id] as $val) {
                $val['level'] = $ncount;
                $tree[$val['id']] = $val;
                $this->_build_tree($list, $val['id'], $tree, $ncount);
            }
        } else {
            return null;
        }
        return $tree;
    }

    /**
     *
     * @param integer $level            
     * @param string $separator            
     * @return string
     */
    public function getLevelCaption ($level, $separator = ' - ')
    {
        static $xhtml;
        
        if (isset($xhtml[$level])) {
            return $xhtml[$level];
        }
        $xhtml[$level] = '';
        for ($i = 0; $i < (int) ($level); $i ++) {
            $xhtml[$level] .= $separator;
        }
        return $xhtml[$level];
    }
}