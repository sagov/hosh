<?php

require_once 'Zend/View/Helper/HeadLink.php';

class Hosh_View_Helper_AddStyleSheet extends Zend_View_Helper_HeadLink
{
	protected $_placement = 'APPEND';
	
	public function AddStyleSheet( $file, $options = array())
	{
		if (empty($file)) return false;	
		
		$config = Hosh_Config::getInstance();
		$file_path = $config->get('public')->path . $file;
		if (file_exists ( $file_path )) {			
			require_once 'Hosh/File.php';
			$cfile = new Hosh_File();
			$file = $cfile->getFilePreff($file);			
			$file = $config->get('public')->url.$file;
		}
		
		if (isset($options['placement'])) {
		    $this->_placement = $options['placement'];
		}
				
		switch (strtoupper($this->_placement))
		{
			case 'PREPEND':
				$this->prependStylesheet($file);
				break;
		
			default:
				$this->appendStylesheet($file);
				
				break;
		}
	}
}