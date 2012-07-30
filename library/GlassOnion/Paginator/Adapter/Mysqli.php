<?php

/**
 * @see Zend_Paginator_Adapter_Interface
 */
require_once 'Zend/Paginator/Adapter/Interface.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Mysqli
 */
class GlassOnion_Paginator_Adapter_Mysqli
    implements Zend_Paginator_Adapter_Interface
{
    /**
     * @var string
     */
    private $sql;

    /**
     * @var GlassOnion_Mysqli
     */
    private $db;

    /**
     * Constructor
     * 
     * @param string $sql
     * @param GlassOnion_Mysqli $db
     */
    public function __construct($sql, GlassOnion_Mysqli $db)
    {
        $this->sql = $sql;
        $this->db = $db;
    }

    /**
     * Returns the total number of rows in the collection.
     *
     * @return integer
     */
    public function count()
    {
        $sql = "SELECT COUNT(1) value FROM ({$this->sql}) AS result";
        $result = $this->db->query($sql);
        return $result->fetch()->value;
    }

    /**
     * Returns an collection of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $sql = "{$this->sql} LIMIT {$itemCountPerPage} OFFSET {$offset}";
        return $this->db->query($sql);
    }
}
