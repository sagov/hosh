<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_AddAction extends Hosh_Form_Helper_Abstract
{
    protected $_placement = 'APPEND';

    public function render ($options)
    {
        $form = $this->getObject();
        
        if (!isset($options['actions']) and !is_array($options['actions']))
        {
            return;
        }
        
        $placement = (isset($placement['placement'])) ? $placement['placement'] : $this->_placement;       
        
        $count = 1000000;
        $i = 0;
        foreach ($options['actions'] as $key=>$val)
        {
            $val['type'] = (isset($val['type'])) ? $val['type'] : 'button';
            $val['name'] = (isset($val['name'])) ? $val['name'] : 'action_'.($key+1);
            $val['class'] = (isset($val['class'])) ? $val['class'] : 'btn btn-primary';
            $val['label'] = (isset($val['label'])) ? $val['label'] : null;
            
            $form->addElement($val['type'], $val['name'],
                    array(
                            'class' => $val['class'],
                            'DisableLoadDefaultDecorators' => true
                    ));
            $element = $form->getElement($val['name']);
            if (isset($val['label'])){
                $element->setLabel($val['label']);
            }
            if ($placement == 'APPEND'){
                $element->setOrder($count+1);
            }
            $decorators = array();
            $decorators['ViewHelper'] = array(
                    'decorator' => 'ViewHelper'
            );
            $decorators['HtmlTag'] = array(
                    'decorator' => 'HtmlTag',
                    'options' => array(
                            'tag' => 'span',
                            'class' => 'item-action-btn'
                    )
            );
            if ($i == 0){
                $decorators['ExtHtmlTag'] = array(
                        'decorator' => 'ExtHtmlTag',
                        'options' => array(
                                'tag' => 'div',
                                'class' => 'wrap-action-btn',
                                'placement'=>'prepend',
                                'openOnly'=>true,
                                
                        )
                );
            }else if ($i == count($options['actions'])){
                $decorators['ExtHtmlTag'] = array(
                        'decorator' => 'ExtHtmlTag',
                        'options' => array(
                                'tag' => 'div',                                
                                'placement'=>'append',
                                'closeOnly'=>true,
                
                        )
                );
            }
            
            $element->addDecorators($decorators);
            ++$count;
            ++$i;
        }
        
        return;
    }
    
    
}    