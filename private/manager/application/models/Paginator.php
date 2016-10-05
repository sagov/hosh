<?php

class HoshManager_Model_Paginator
{
    protected $_view = 'index/pagination/default.phtml';
    protected $_totalcount = 0;
    protected $_currentpagenumber = 1;
    protected $_defaultItemCountPage = 10;
    
    public function getPaginatorFactory()
    {
        $paginator =  Zend_Paginator::factory($this->_totalcount);
        return $paginator;
        
    }
    
    public function setView($name)
    {
        $this->_view = $name;
        return $this;
    }
    
    public function getView()
    {
        return $this->_view;
    }
    
    public function setTotalCount($count)
    {
        $this->_totalcount = $count;
        return $this;
    }
    
    public function getTotalCount(){
        return $this->_totalcount;
    }
    
    public function setCurrentPageNumber($number)
    {
        $this->_currentpagenumber = $number;
        return $this;
    }
    
    public function getCurrentPageNumber(){
        return $this->_currentpagenumber;
    }
    
    public function setDefaultItemCountPage($number)
    {
        $this->_defaultItemCountPage = $number;
        return $this;
    }
    
    public function getDefaultItemCountPage(){
        return $this->_defaultItemCountPage;
    }
    
    public function run()
    {
        $paginator = $this->getPaginatorFactory();
        $paginator->setCurrentPageNumber($this->_currentpagenumber);
        $paginator->setDefaultItemCountPerPage($this->_defaultItemCountPage);
        $view = Zend_View_Helper_PaginationControl::setDefaultViewPartial($this->_view);
        $paginator->setView($view);
        return $paginator;
    }
    
}