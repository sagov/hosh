<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_JQuery_Datetimepicker extends Zend_View_Helper_HeadScript
{
	public function JQuery_Datetimepicker()
	{
		$this->view->JQuery();
		$this->view->AddScript('/libraries/jquery/plugins/datetimepicker/build/jquery.datetimepicker.full.min.js');
		$this->view->AddStyleSheet('/libraries/jquery/plugins/datetimepicker/jquery.datetimepicker.css');
		return $this;
	}
}