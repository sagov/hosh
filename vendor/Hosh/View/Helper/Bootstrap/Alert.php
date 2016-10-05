<?php

require_once 'Zend/View/Helper/HtmlElement.php';

class Hosh_View_Helper_Bootstrap_Alert extends Zend_View_Helper_HtmlElement
{
	
	protected $options = array(
		'close'=>true,
		'kind'=>'success',
		'dismissable'=>true,	
	);
	
	public function Bootstrap_Alert($string, array $options = array()){
		
		$options = array_merge($this->options,$options);
		$class = array();
		if ($options['dismissable']) $class[] = 'alert-dismissable'; 
		
		if (empty($options['kind'])) $options['kind'] = 'success';
		
		$xhtml = null;
		$xhtml .= '<div class="alert alert-'.$options['kind'].' '.implode(' ',$class).'">';
		if ($options['close']) $xhtml .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$xhtml .= $string;
		$xhtml .= '</div>';
		return $xhtml;
	}
}	