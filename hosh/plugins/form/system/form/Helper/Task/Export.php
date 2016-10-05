<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Task_Export extends Hosh_Form_Helper_Abstract
{
	public function render($options){
	    $form = $this->getObject();	
	    $idform = $form->getData('sname');
	    Header('Content-Type: application/octet-stream');
	    Header('Accept-Ranges: bytes');
	    Header('Content-disposition: attachment; filename="'.$form->getData('sname').'.xml"');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');    
	    $result = $this->_getXmlData($idform);	 
	    echo $result;   
	    exit;
	}
	
	protected function _getXmlData($idform)
	{
	   	     
	    $_form = new Hosh_Form($idform);
	    $_form->initialize();
	    $pattern = $_form->getPattern();
	    $data_pattern = $pattern->getData();
	    $elements = $pattern->getElements();
	    $arr_element = array();
	    foreach ($elements as $element){
	        $arr_element[] = $element->getData();
	    }
	    $data['data'] = $data_pattern;
	    if (!empty($data_pattern['idclone'])){
	        $manager_form = new Hosh_Manager_Form();
	        $form_data = $manager_form->getObject($data_pattern['idclone']);
	        $data['data']['idclone'] = $form_data['sname'];
	    }
	    $data['elements'] = $arr_element;         
	     
	    $doc = new DOMDocument('1.0','UTF-8');
	    $doc->formatOutput = true;
	    $root = $doc->createElement('document');
	    $root->setAttribute('date', date('Y-m-d H:i:s'));
	    $root = $doc->appendChild($root);
	     
	    $typeexport = $doc->createElement('type');
	    $typeexport = $root->appendChild($typeexport);
	    $text = $doc->createTextNode('hosh_form');
	    $text = $typeexport->appendChild($text);	     
	     
	    $userauth = Hosh_Manager_User_Auth::getInstance();
	    $userauth_data = $userauth->getData();
	    $author = $doc->createElement('author');
	    $author = $root->appendChild($author);
	    $text = $doc->createTextNode($userauth_data['susername']);
	    $text = $author->appendChild($text);
	     
	     
	    $this->createXMLDom($doc,$root,$data);
	    $options = $doc->saveXML($root);
	    $xml_result = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	    $xml_result .= $options;
	    return $xml_result;
	}
	
	private function createXMLDom(DOMDocument $doc, $root, array $array){
	
	    foreach ($array as $key=>$val){
	
	        if (is_array($val)){
	            if (!is_string($key)) {
	                $val['id'] = (string)($key);
	                $key = 'item';	                
	            }
	            $r = $doc->createElement($key);
	            $r = $root->appendChild($r);	
	            $this->createXMLDom($doc,$r,$val);
	        }else{
	            $title = $doc->createElement($key);
	            $title = $root->appendChild($title);
	            $text = $doc->createTextNode($val);
	            $text = $title->appendChild($text);
	        }
	    }
	
	}
}	