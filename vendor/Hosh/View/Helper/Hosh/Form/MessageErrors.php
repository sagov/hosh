<?php

require_once 'Zend/View/Helper/HtmlElement.php';

class Hosh_View_Helper_Hosh_Form_MessageErrors extends Zend_View_Helper_HtmlElement
{	
	
	
	public function Hosh_Form_MessageErrors(Zend_Form  $form)
	{
	    $errors = $form->getErrors();
	    $messages = $form->getMessages();
	    $error_messages = $form->getErrorMessages();
	     
	    $translator = $form->getTranslator();
	    	    
	     
	    $xhtml_element = $xhtml_error = array();
	    foreach ($messages as $key=>$val_error)
	    {
	        $element = $form->getElement($key);
	        $label = $element->getLabel();
	        $msg_element = null;
	        $msg_element .= '<div class="msgerror-element msg-element-'.$key.'">';
	        $msg_element .= '<div class="msgerror-element-label">'.$label.'</div>';
	        foreach ($val_error as $val){
	            $msg_element .= '<div class="msgerror-element-item">'.$val.'</div>';
	        }
	        $msg_element .= '</div>';
	         
	        $xhtml_element[] = $msg_element;
	    }
	    foreach ($error_messages as $val)
	    {
	        $xhtml_error[] = '<div class="msgerror-error">'.$val.'</div>';
	    }
	     
	    $xhtml = null;
	     
	    if (count($xhtml_error)>0){
	        $xhtml .= '<div class="msgerror-error">'.implode('', $xhtml_error).'</div>';
	    }
	     
	    if (count($xhtml_element)>0){
	        $xhtml .= '<div class="msgerror-elements">';
	        $xhtml .= '<div class="msgerror-caption">'.$translator->_('SYS_FORM_MSGERROR_ELEMENT_CAPTION').':</div>';
	        $xhtml .= implode('', $xhtml_element);
	        $xhtml .= '</div>';
	    }
	    return $xhtml;
	}
}	