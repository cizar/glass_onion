<?php

require_once 'GlassOnion/Controller/Crud.php';
require_once 'GlassOnion/Paginator/Adapter/DoctrineQuery.php';

abstract class GlassOnion_Controller_Crud_Doctrine extends GlassOnion_Controller_Crud
{
	protected $_itemCountPerPage = 20;
	
	protected $_record_class = null;
	
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
		$query = Doctrine_Query::create()
			->from($this->_record_class);

		$this->filerIndexQuery($query);

		$this->orderIndexQuery($query);

		return $query;
	} 

	/**
	 * @return void
	 */
	public function showAction()
	{
		$record = $this->getRecord($this->_getParam('id'));
		$this->record = $record;
		$this->view->record = $record;
	}

	/**
	 * @return void
	 */
	public function newAction()
	{
		if (!method_exists($this, 'create'))
		{
			$signature = get_class($this) . '::create(Doctrine_Record $record)';
			throw new Exception("Method {$signature} not implemented");
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

		$this->record = $record;
		$this->view->record = $record;
	}

	/**
	 * @return void
	 */
	public function editAction()
	{
		if (!method_exists($this, 'update'))
		{
			$signature = get_class($this) . '::update(Doctrine_Record $record)';
			throw new Exception("Method {$signature} not implemented");
		}

		$record = $this->getRecord($this->_getParam('id'));

		if ($this->_request->isPost())
		{
			try
			{
				$this->update($record);
				$record->save();
				$this->_helper->redirector();
			}
			catch (Doctrine_Validator_Exception $ex)
			{
				$this->view->invalidRecords = $ex->getInvalidRecords();
			}
		}

		$this->record = $record;
		$this->view->record = $record;
	}

	/**
	 * @return void
	 */
	public function deleteAction()
	{
		$record = $this->getRecord($this->_getParam('id'));

		$record->delete();

		if (method_exists($this, 'destroy'))
		{
			$this->destroy($record);
		}

		$this->_helper->redirector('index');
	}

	/**
	 * @return void
	 */
	protected function useModel($class)
	{
		if (!class_exists($class))
		{
			throw new Exception('Unable to load class ' . $class);
		}
		
		$this->_record_class = $class;
	}

	/**
	 * @return Doctrine_Record
	 */
	protected function getNewRecord()
	{
		$record = new $this->_record_class;

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

	/**
	 * @return Doctrine_Table
	 */
	protected function getTable()
    {
		return Doctrine_Core::getTable($this->_record_class);   
	}
}