<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Application
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Tab.php 21.04.2016 17:59:59
 */

/**
 * Tabs
 *
 * @category Hosh
 * @package Hosh_Application
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Application_Tab
{

    /**
     * @var unknown
     */
    protected $_options = array(
            'id' => 'Tab',
            'view' => 'Bootstrap_Tab'
    );

    /**
     * @var unknown
     */
    protected $_item;

    /**
     * @param string $id
     * @return Hosh_Application_Tab
     */
    public function __construct ($id)
    {
        $this->setOption('id', $id);
        return $this;
    }

    /**
     * @param string $key
     * @param string $title
     * @param string $content
     * @param array $options
     * @return Hosh_Application_Tab
     */
    public function setItem ($key, $title, $content, $options = null)
    {
        $this->_item[$key] = array(
                'title' => $title,
                'content' => $content
        );
        return $this;
    }

    /**
     * @param string $key
     * @return unknown|boolean
     */
    public function getItem ($key)
    {
        if (isset($this->_item[$key])) {
            return $this->_item[$key];
        }        
        return false;
    }

    /**
     * @return unknown
     */
    public function getItems ()
    {
        return $this->_item;
    }

    /**
     * @param string $key
     * @return Hosh_Application_Tab
     */
    public function removeItem ($key)
    {
        if (isset($this->_item[$key])) {
            unset($this->_item[$key]);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return Hosh_Application_Tab
     */
    public function setOption ($key, $value)
    {
        $this->_options[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param string $default
     * @return unknown|string
     */
    public function getOption ($key, $default = null)
    {
        if (isset($this->_options[$key])) {
            return $this->_options[$key];
        } else {
            return $default;
        }
    }

    /**
     * @return string
     */
    public function render ()
    {
        $view = Hosh_View::getInstance();
        $nameview = $this->getOption('view', 'Bootstrap_Tab');
        return $view->$nameview($this);
    }
}