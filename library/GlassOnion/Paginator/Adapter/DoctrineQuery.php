<?php

class GlassOnion_Paginator_Adapter_DoctrineQuery implements Zend_Paginator_Adapter_Interface
{
    protected $_query;

    public function __construct(Doctrine_Query_Abstract $query)
    {
        $this->_query = $query;
    }

    public function count()
    {
        $query = clone($this->_query);
        return $query->count();
    }

    public function getItems($offset, $itemCountPerPage)
    {
        return $this->_query
            ->limit($itemCountPerPage)
            ->offset($offset)
            ->execute();
    }
}