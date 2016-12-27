<?php
require_once 'Hosh/Form/Helper/Abstract.php';

/**
 *
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *           
 */
class Hosh_Form_Helper_Decorator_Element extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $element_name = $options['element_name'];
        $element = $form->getElement($element_name);
        
        if (! $element) {
            return false;
        }
        $decorator['HtmlTag2'] = array(
            'decorator' => 'HtmlTag2',
            'options' => array(
                'tag'=>'div','class'=>'value-element'
            )
        );
        $result['HtmlTag'] = array(
                'decorator' => 'HtmlTag',
                'options' => array(
                        'tag' => 'div',
                        'class' => 'Wrap-Element'
                )
        );
        $result['Label'] = array(
                'decorator' => 'Label',
                'options' => array(
                        'class' => 'labelElement',
                        'escape' => false
                )
        );
        $result['ExtHtmlTag'] = array(
                'decorator' => 'ExtHtmlTag',
                'options' => array(
                        'tag' => 'div',
                        'class' => 'Wrap-Element-Group element-wrap-' .
                                 $element_name
                )
        );
        
        if ($element instanceof Zend_Form_Element_Checkbox) {
            $result['HtmlTag']['options']['tag'] = 'span';
            $result['Label']['options']['placement'] = 'APPEND';
        }
        $element->addDecorators($result);
        
        $allowempty = $element->getAllowEmpty();
        if ( !$allowempty) {
            $label = $element->getDecorator('Label'); 
            $label->setOptSuffix('<span class="isnotempty-flag">*</span>');
        }
        return $element;
    }
}