<?php

/**
 * @see Zend_Paginator_Adapter_Interface
 */
require_once 'Zend/Paginator/Adapter/Interface.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Paginator
 */
class GlassOnion_Paginator_Adapter_DoctrineQuery
    implements Zend_Paginator_Adapter_Interface
{
	/**
	 * @var Doctrine_Query_Abstract
	 */
	protected $query;

	/**
	 * Constructor
	 *
	 * @param Doctrine_Query_Abstract $query
	 * @return void
	 */
	public function __construct(Doctrine_Query_Abstract $query)
	{
		$this->query = $query;
	}

	/**
	 * Returns the total number of rows in the result.
	 *
	 * @return integer
	 */
	public function count()
	{
		$query = clone($this->query);
		return $query->count();
	}

	/**
	 * Returns an array of items for a page.
	 *
	 * @param  integer $offset Page offset
	 * @param  integer $itemCountPerPage Number of items per page
	 * @return array
	 */
	public function getItems($offset, $itemCountPerPage)
	{
		return $this->query
			->limit($itemCountPerPage)
			->offset($offset)
			->execute();
	}
}
