<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: GetKeyFromArray.php 21.04.2016 18:23:41
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Form Filter GetKeyFromArray
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Filter
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Filter_GetKeyFromArray implements Zend_Filter_Interface
{

    /**
     *
     * @var unknown
     */
    protected $MultiOptions;

    /**
     *
     * @param array $options            
     */
    public function __construct ($options = null)
    {
        $this->MultiOptions = $options;
    }
    
    /*
     * (non-PHPdoc) @see Zend_Filter_Interface::filter()
     */
    public function filter ($value)
    {
        if (isset($this->MultiOptions[$value]))
            return $this->MultiOptions[$value];
        else
            return $value;
    }
}