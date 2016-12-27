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

        if (count($options['actions']) == 0){
            return;
        }
        
        $placement = (isset($placement['placement'])) ? $placement['placement'] : $this->_placement;       
        
        $count = 1000000;
        $i = 0;
        $listelements = array();
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
            
            $element->addDecorators($decorators);
            $listelements[] = $val['name'];

            ++$count;
            ++$i;
        }
        if (count($listelements)>0) {
            $form->addDisplayGroup($listelements, 'FLAction', array('order' => 100000, 'DisableLoadDefaultDecorators' => true));
            $displaygroup = $form->getDisplayGroup('FLAction');
            $displaygroup->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'wrap-action-btn'));
        }

        return;
    }
    
    
}    