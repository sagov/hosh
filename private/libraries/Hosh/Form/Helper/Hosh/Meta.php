<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Meta.php 08.04.2016 17:48:17
 */
require_once 'Hosh/Form/Helper/Abstract.php';

/**
 * Description of file_name
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Helper
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Helper_Hosh_Meta extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $translate = $form->getTranslator();
        $result = array();
        if ($form->isEdit()) {
            $key = 'update';
        } else {
            $key = 'insert';
        }
        if (isset($options[$key]['title'])) {            
            $data = $form->getDataAll();
            $result['title'] = $translate->_($options[$key]['title']);
            $result['title'] = preg_replace("/:([A-Za-z0-9_]{1,})[^A-Za-z0-9_]{0}/e", "\$data['\\1']", $result['title'] );
        } else {
            $pattern = $form->getPattern();
            $result['title'] = $translate->_($pattern->get('scaption'));            
        }
        
        return $result;
    }
}	