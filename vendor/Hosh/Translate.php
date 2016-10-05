<?php

require_once 'Zend/Translate.php';

class Hosh_Translate 
{
	protected static $_translate;

	protected $options = array(
		'adapter'=>'Hosh_Translate_Adapter_Hosh',
		'content'=>null,
		'locale'=>null,
		'disableNotices'=>true,		
			
	);
	
	const LOCALE_DEFAULT = 'en';
	
	/**
	 * @var unknown
	 */
	protected static $_instance = null;
	
	/**
	 * @return Hosh_Translate
	 */
	public static function getInstance ()
	{
	    if (null === self::$_instance) {
	        self::$_instance = new self();
	    }
	    return self::$_instance;
	}
	
	/**
	 * @return Zend_Translate
	 */
	public function getTranslate(){
		if (!isset(self::$_translate)){
			$this->_setTranslate();
		}
		return self::$_translate;
	}
	
	public function setTranslate(Zend_Translate $translate)
	{
	    self::$_translate = $translate;
	    return $this;
	}
	
	protected function _setTranslate(){
		require_once 'Zend/Translate.php';
		$config = Hosh_Config::getInstance();
		$path = $config->get('path');
		$this->options['locale'] = $this->_getLocale();
		$this->options['content'] = '_';				
		self::$_translate = new Zend_Translate($this->options);
		return $this;
	}
	
	public function setOptions($options){
		$this->options = array_merge($this->options,$options);
	}
	
	public function load($name){
		$translate = $this->getTranslate();
		$adapter = $translate->getAdapter();
		$adapter->addTranslation($name,$adapter->getLocale());
		return $this;
	}
	
	public function getAdapter(){
		$translate = $this->getTranslate();
		$adapter = $translate->getAdapter();
		return $adapter;
	}
	
	
	protected function _getLocale(){
	    $lang = Hosh_Manager_Language_Self::getInstance();
	    $locale = $lang->getCode();		
		$zlocale = new Zend_Locale();
		if (!$zlocale->isLocale($locale)) {
		    $locale = self::LOCALE_DEFAULT;
		}		
		return $locale;
	}
	
	/**
	 * translate plural
	 * @param array $messageId
	 * @param int $number
	 * @return string
	 */
	public function getPlural(array $messageId,$number){
		$locale = $this->getAdapter()->getLocale();
		$rule = Zend_Translate_Plural::getPlural($number, $locale);
		if (isset($messageId[$rule])) {
		    return $messageId[$rule];
		}else{
		    return $messageId[0];
		}
	}
	
}