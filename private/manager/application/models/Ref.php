<?php

class HoshManager_Model_Ref
{
    protected $_countList = 10;
    
    public function getList (Hosh_Manager_Db_Table_Abstract $package, $param = null)
    {               
        $paramlist = $this->_getParamList($param);
        $list = $package->getList($paramlist);
        return $list;        
    }
    
    public function getTotalCountList(Hosh_Manager_Db_Table_Abstract $package, $param = null)
    {
        $paramlist = $this->_getParamList($param);
        $totalcount = (int) ($package->getCount($paramlist));
        return $totalcount;
    }
    
    public function getPagination($totalcount,  $currentpage = 1, $count = 10)
    {
        if ($totalcount > $count) {
            $paginator = new HoshManager_Model_Paginator_Select();
            $paginator->setTotalCount($totalcount)
            ->setDefaultItemCountPage($count)
            ->setCurrentPageNumber($currentpage);
            return $paginator;
        }
        return false;
    }
    
    protected function _getParamList($param = null)
    {
        if (! isset($param['search'])){
            $param['search'] = null;
        }
        if (! isset($param['page'])){
            $param['page'] = 1;
        }
        if (! isset($param['count'])){
            $param['count'] = $this->_countList;
        }
        $offset = ($param['page'] - 1) * $param['count'];
        $paramlist = array(
                'sname' => $param['search'],
                'offset' => $offset,
                'count' => $param['count']
        );
        return $paramlist;
    }
}