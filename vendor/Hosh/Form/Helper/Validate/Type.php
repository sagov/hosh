<?php

require_once 'Hosh/Form/Helper/Validate/Regex.php';

class Hosh_Form_Helper_Validate_Type extends Hosh_Form_Helper_Validate_Regex
{
    protected $regex_value = '/[0-9A-z_]/i';
    protected $regex_js = '[0-9A-z_]*';
    protected $bcheckjs = true;
    
    protected $_type = array(
        'int' => array('regex'=>'/[0-9]/i','regex_js'=>'[0-9]*'),
        'price' => array('regex'=>'/[0-9]\.[0-9]{0,2}/i','regex_js'=>'[0-9]*'),
    );
    
    public function render($options)
    {
        parent::render($options);
    }
    
}