<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_AddScript extends Zend_View_Helper_HeadScript
{	
	protected $_placement = 'APPEND';
	
	public function AddScript( $file, $options = array(), $preff = true)
	{
		if (empty($file)) {
		    return false;		
		}
		
		$config = Hosh_Config::getInstance();
		
		$file_path = $config->get('public')->path . $file;
		if (file_exists ( $file_path )) {		    
		    if ($preff){			
			     require_once 'Hosh/File.php';
			     $cfile = new Hosh_File();
			     $file = $cfile->getFilePreff($file);
		    }
		    $file = $config->get('public')->url.$file;
		}
		
		if (isset($options['placement'])) {
		    $this->_placement = $options['placement'];
		}
				
		switch (strtoupper($this->_placement))
		{
			case 'PREPEND':
				$this->prependFile($file);
				break;
		
			default:
				$this->appendFile($file);				
				break;
		}
	}
}