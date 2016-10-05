<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_JQuery_Dmuploader extends Zend_View_Helper_HeadScript
{
	public function JQuery_Dmuploader()
	{
		$this->view->JQuery();
		$this->view->AddScript('/libraries/jquery/plugins/dmuploader/0.1/dmuploader.min.js');
		$this->view->AddStyleSheet('/libraries/jquery/plugins/dmuploader/0.1/uploader.css');
		return $this;
	}
}