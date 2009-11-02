<?php

require_once 'GlassOnion/Controller/Crud.php';
require_once 'GlassOnion/Paginator/Adapter/DoctrineQuery.php';
require_once 'GlassOnion/Filter/LowerCaseFirst.php';

abstract class GlassOnion_Controller_Crud_Doctrine extends GlassOnion_Controller_Crud
{
	protected $_query = null;
	
	protected $_itemCountPerPage = 20;
	
	abstract protected function getTableName();

	public function indexAction()
	{
		$query = $this->getIndexQuery();

		$paginator = new Zend_Paginator(
			new GlassOnion_Paginator_Adapter_DoctrineQuery($query));

		$paginator->setItemCountPerPage($this->_itemCountPerPage)
			->setCurrentPageNumber($this->_request->getParam('page', 1));

		$this->view->records = $paginator;
	}

	protected function orderIndexQuery(Doctrine_Query $query)
	{
		$order = $this->_request->getParam('order', null);
		$order_asc = $this->_request->getParam('order_asc', 'asc');

		is_null($order)
			or $query->orderBy($order . ' ' . $order_asc);

		$this->view->order = array('field' => $order, 'asc' => $order_asc);
	}

	protected function filerIndexQuery(Doctrine_Query $query)
	{
		// Hook to filter the index query
	}
    
	/**
	 * @return Doctrine_Query
	 */
	protected function getIndexQuery()
	{
		if (is_null($this->_query))
		{
			$this->_query = Doctrine_Query::create()
				->from($this->getTableName());

			$this->filerIndexQuery($this->_query);

			$this->orderIndexQuery($this->_query);
		}

		return $this->_query;
	} 

	/**
	 * @return void
	 */
	public function showAction()
	{
		$record = $this->getRecord($this->_getParam('id'));
		$this->assignRecord($record);
	}

	/**
	 * @return void
	 */
	public function newAction()
	{
		if (!method_exists($this, 'create'))
		{
			$signature = get_class($this) . '::create(Doctrine_Record $record)';
			throw new Exception("Must be implemented {$signature}");
		}

		$record = $this->getNewRecord();

		if ($this->_request->isPost())
		{
			try
			{
				$this->create($record);
				$record->save();
				$this->_helper->redirector();
			}
			catch (Doctrine_Validator_Exception $ex)
			{
				$this->view->invalidRecords = $ex->getInvalidRecords();
			}
		}

		$this->assignRecord($record);
	}

	/**
	 * @return void
	 */
	public function editAction()
	{
		$record = $this->getRecord($this->_getParam('id'));

		if ($this->_request->isPost())
		{
			try
			{
				if (!method_exists($this, 'update'))
				{
					$signature = get_class($this) . '::update(Doctrine_Record $record)';
					throw new Exception("Must be implemented {$signature}");
				}

				$this->update($record);
				$record->save();
				$this->_helper->redirector();
			}
			catch (Doctrine_Validator_Exception $ex)
			{
				$this->view->invalidRecords = $ex->getInvalidRecords();
			}
		}

		$this->assignRecord($record);
	}

	/**
	 * @return void
	 */
	public function deleteAction()
	{
		$record = $this->getRecord($this->_getParam('id'));

		if (method_exists($this, 'destroy'))
		{
			$this->destroy($record);
		}

		$record->delete();

		$this->_helper->redirector('index');
	}

	/**
	 * @return Doctrine_Record
	 */
	protected function getNewRecord()
	{
		$className = $this->getTableName();

		if (!class_exists($className))
		{
			throw new Exception('Unable to load class ' . $className);
		}

		$record = new $className;

		if (!is_subclass_of($record, 'Doctrine_Record'))
		{
			throw new Exception('The object is not a Doctrine_Record');
		}

		return $record;
    }
    
	/**
	 * @return Doctrine_Record
	 */
	protected function getRecord($id = null)
	{
		if (is_null($id))
		{
			return $this->getNewRecord();
		}

		return $this->getTable()->find($id);
    }

	protected function getTable()
    {
		return Doctrine::getTable($this->getTableName());   
	}

	protected function assignRecord(Doctrine_Record $record)
	{
		$lcf = new GlassOnion_Filter_LowerCaseFirst();
		$property = $lcf->filter($this->getTableName());

		$this->$property = $record;
		$this->view->assign($property, $record);

	}
}