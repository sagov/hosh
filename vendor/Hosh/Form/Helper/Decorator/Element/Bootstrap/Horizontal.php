<?php

/**
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */

require_once 'Hosh/Form/Helper/Decorator/Element/Bootstrap.php';

class Hosh_Form_Helper_Decorator_Element_Bootstrap_Horizontal extends Hosh_Form_Helper_Decorator_Element_Bootstrap
{
	public function render($options){	
		
		parent::render($options);
		
		$form = $this->getObject();
		$element_name = $options['element_name'];		
		$element = $form->getElement($element_name);		
	
		$_decorator = $element->getDecorator('HtmlTag');
		$_decorator->setOption('class', $_decorator->getOption('class').' col-lg-9 col-sm-8');
		
		$_decorator = $element->getDecorator('Label');
		$_decorator->setOption('class', $_decorator->getOption('class').' col-lg-3 col-sm-4');
		$element->removeDecorator('ExtHtmlTag');
		$result = array();
		
		$result['HtmlTag2'] = array(
				'decorator' => 'HtmlTag2',
				'options' => array(
						'tag' => 'div',
						'class' => 'row'
				)
		);
		$result['ExtHtmlTag'] = array(
				'decorator' => 'ExtHtmlTag',
				'options' => array(
						'tag' => 'div',
						'class' => 'Wrap-Element-Group level-horizontal element-wrap-' .
						$element_name
				)
		);		
		
		$element->addDecorators($result);
		
		if ($element instanceof Zend_Form_Element_Checkbox) {
			$_decorator = $element->getDecorator('HtmlTag');
			$_decorator->setOption('tag', 'div');
			$_decorator = $element->getDecorator('Label');
			$_decorator->setOption('placement', 'PREPEND');
		}
		
		return $element;
	}
	
	
}