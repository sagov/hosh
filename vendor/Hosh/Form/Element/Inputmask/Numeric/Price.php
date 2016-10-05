<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Price.php 21.04.2016 18:23:41
 */
require_once 'Hosh/Form/Element/Inputmask/Numeric.php';
/**
 * Form Element Numeric_Price
 * 
 * @category   Hosh
 * @package     Hosh_Form
 * @subpackage  Element
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Form_Element_Inputmask_Numeric_Price extends Hosh_Form_Element_Inputmask_Numeric
{
    protected $_typedata = 'decimal';
    protected $_digits = 2;
    protected $_groupSeparator = ' ';      
   
}    