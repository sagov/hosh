<?php

require_once 'Zend/View/Helper/HtmlElement.php';

class Hosh_View_Helper_Bootstrap_Modal extends Zend_View_Helper_HtmlElement
{
	protected $options = array(
		'version'=>'1.0.1',	
		'path'=>'/libraries/app/modal/bootstrap/',
	);
	
		
	public function Bootstrap_Modal(){
		return $this;
	}
	
	public function addHeadScript(){
		$view = Hosh_View::getInstance();		
		
		$view->AddScript($this->options['path'].$this->options['version'].'/modal.js');
		$view->AddStyleSheet($this->options['path'].$this->options['version'].'/modal.css');
		return $this;
	}	
	
	public function render($id,$content,$options = null){
		
		if (! isset ( $options ['size'] ))	$options ['size'] = null;
		if (!isset($options ['title'])) $options ['title'] = '&nbsp;';
		if (isset($options ['hidehead'])) $head = false; else $head = true;
		if (!isset($options ['close'])) $options ['close'] = 1;
		$options['hidebackdrop'] = (!isset($options['hidebackdrop'])) ? true : false;
		
		$this->view->Bootstrap();
		
		
		
		$xhtml = null;
		$xhtml .= '<div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog" aria-labelledby="' . $id . 'Label" aria-hidden="true"  '.(true==$options['hidebackdrop']?'data-backdrop="false"':'').'>
					<div class="modal-dialog ' . $options ['size'] . '">
						<div class="modal-content">';
		if ($head){
			$xhtml .= '<div class="modal-header">';
			if (!empty($options ['close']))	$xhtml .= '	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			$xhtml .= '	<h4 class="modal-title" id="' . $id . 'Label">' . $options ['title'] . '</h4>
						</div>';
		}
		
		$xhtml .= '<div class="modal-body">
							' . $content . '
							</div>';
		
		if (isset($options['footer'])){
			$xhtml .= '<div class="modal-footer">'.$options['footer'].'</div>';
		}
		
		$xhtml .= '</div>
					</div>
				</div>';
		return $xhtml;
	}
}