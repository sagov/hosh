<?php

require_once 'Hosh/Form/Helper/Validate/Regex.php';

class Hosh_Form_Helper_Validate_Code extends Hosh_Form_Helper_Validate_Regex
{
    protected $regex_value = '/[0-9A-z_]/i';
    protected $regex_js = '[0-9A-z_]*';
    protected $bcheckjs = true;
    
}