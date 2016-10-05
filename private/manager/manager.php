<?php

/**
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */


class HoshManager
{	
	
	/**
	 * Default Module
	 * @var string
	 */
	protected $_module = 'hoshmanager';
	/**
	 * Default Controller
	 * @var string
	 */
	protected $_controller;
	/**
	 * Default Action
	 * @var string
	 */
	protected $_action;
	/**
	 * Layout Path
	 * @var string
	 */
	protected $_layoutdir = 'default';
	/**
	 * Layout
	 * @var string
	 */
	protected $_layout = 'index';
	
	/**
	 * Language
	 * @var unknown
	 */
	protected $_lang;
	
	protected $_route;
	
	public function __construct()
	{
	    require_once dirName(__FILE__).'/../factory.php';
	    HoshFactory::run();
	}
		
	public function run(){	   
		$this->runLayout();
		$this->runLanguage();
		$this->ControllerDispatch();				
	}
	
	
	/**
	 * @param string $name
	 * @param string $layout
	 * @return HoshManager
	 */
	public function setLayout( $name, $layout = 'index'){
		$this->_layout = $layout;
		$this->_layoutdir = $name;
		return $this;
	}
	
	/**
	 * @param string $code
	 * @return HoshManager
	 */
	public function setLanguage($code){
	    $lang = Hosh_Manager_Language_Self::getInstance();
	    $list = $lang->getAdapter()->getListAccess();
	    if ($list[$code]){
	       $this->_lang = $code;
	    }
	    return $this;
	}
	
	/**
	 * @return HoshManager
	 */
	public function runLanguage(){
	    if (!empty($this->_lang)){
	       $lang = Hosh_Manager_Language_Self::getInstance();
	       $lang->set($this->_lang);
	    }
	    return $this;
	} 
	
	/**
	 * @return HoshManager
	 */
	public function runLayout(){
		$layout = Zend_Layout::startMvc();
		$layout->setLayout($this->_layout)
		->setLayoutPath(dirName(__FILE__).'/layouts/'.$this->_layoutdir)
		->setContentKey('content');
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return HoshManager
	 */
	public function setModule($name){
		$this->_module = $name;
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return HoshManager
	 */
	public function setController($name){
		$this->_controller = $name;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getController(){
	    return $this->_controller;
	}
	
	/**
	 * @param string $name
	 * @return HoshManager
	 */
	public function setAction($name){
		$this->_action = $name;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getAction()
	{
	    return $this->_action;
	}
	
	/**
	 * @param Zend_Controller_Router_Route_Abstract $route
	 * @return HoshManager
	 */
	public function setRoute(Zend_Controller_Router_Route_Abstract $route)
	{
	    $this->_route = $route;
	    return $this;
	}
	
	/**
	 * @return HoshManager
	 */
	public function ControllerDispatch(){
	    	    	    
		$_controller = Zend_Controller_Front::getInstance();				
		$_controller->addModuleDirectory(dirName(__FILE__).'/application')
		->setParam('prefixDefaultModule', true)
		->setDefaultModule($this->_module);
		
		
		if (isset($this->_route)){
		    $router = $_controller->getRouter();
		    $router->addRoute($this->_route->assemble(), $this->_route);
		    $_controller->setRouter($router);
		}
		
		if (!empty($this->_controller)) {
		    $_controller->setDefaultControllerName($this->_controller);
		}
		if (!empty($this->_action)) {
		    $_controller->setDefaultAction($this->_action);
		}
		
		$loader = new Zend_Loader_Autoloader_Resource(array(
		            'namespace' => 'HoshManager_',
		            'basePath'  => dirName(__FILE__).'/application',
		         ));
		$loader->addResourceType('Model', 'models', 'Model');	
		
		$_controller->dispatch();
		return $this;
	}
	
}