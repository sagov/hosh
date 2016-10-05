<?php

class HoshPluginForm_System_Formelement_Element_Checkbox extends Zend_Form_Element_Checkbox
{
    /**
     * Options that will be passed to the view helper
     * @var array
     */
    public $options = array(
            'checkedValue'   => '1',
            'uncheckedValue' => NULL,
    );    
       
    /**
     * Value when not checked
     * @var string
     */
    protected $_uncheckedValue = NULL;
    
    /**
     * Current value
     * @var string NULL or 1
     */
    protected $_value = NULL;
}