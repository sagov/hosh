<?php

require_once 'Zend/View/Helper/HtmlElement.php';

class Hosh_View_Helper_Hosh_Alert extends Zend_View_Helper_HtmlElement
{	
	
	
	public function Hosh_Alert()
	{
		$a = Hosh_Controller_Action_Helper_Alert::getInstance();
		$alert_arr = $a->get();
		if (!$alert_arr){
		    return ;
		}
		
		$alert = array();
		foreach ($alert_arr as $key=>$row){
			foreach ($row as $val){
				$alert[$key][] = '<div class="alert-item">'.$val.'</div>';
			}
		}
		
		$xhtml = null;
		foreach ($alert as $key=>$val){
			$xhtml .= $this->view->Bootstrap_Alert(implode('',$val),array('kind'=>$key));
		}
		return $xhtml;
	}
}	