<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Number.php 21.04.2016 18:23:41
 */
require_once 'Zend/Form/Element/Text.php';

/**
 * Form Element Value
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Element
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Element_Color extends Zend_Form_Element_Text 
{
	/**
	 *
	 * @var unknown
	 */
	public $helper = 'Form_Color';
}