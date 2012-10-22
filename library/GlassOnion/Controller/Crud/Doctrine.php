<?php

/**
 * @see GlassOnion_Controller_Crud_Interface
 */
require_once 'GlassOnion/Controller/Crud/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 */
abstract class GlassOnion_Controller_Crud_Doctrine
    extends GlassOnion_Controller_Crud_Abstract
{
    /**
     * The class name of the model
     *
     * @var string
     */
    private $_tableName = null;

    /**
     * The number of items per page
     *
     * @var integer
     */
    private $_itemCountPerPage = 30;
    
    /**
     * Auto-Redirect when listing only returns one match
     *
     * @var boolean
     */
    private $_oneMatchRedirect = true;
    
    /**
     * The default sorting data
     *
     * @var string
     */
    private $_sortDefault = null;

    /**
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getIndexQuery();

        /**
         * @see GlassOnion_Paginator_Adapter_DoctrineQuery
         */
        require_once 'GlassOnion/Paginator/Adapter/DoctrineQuery.php';
        $paginator = new Zend_Paginator(
            new GlassOnion_Paginator_Adapter_DoctrineQuery($query));

        $paginator->setItemCountPerPage($this->_itemCountPerPage)
                  ->setCurrentPageNumber($this->_getParam('page', 1));

        if (
            $this->_oneMatchRedirect
            && $this->_queryIsFiltered($query)
            && 1 == $paginator->getTotalItemCount()
        ) {
            $this->_helper->redirector('show', null, null,
                array('id' => $id = $paginator->getItem(1)->id));
        }

        $this->view->records = $paginator;
    }

    /**
     * @return void
     */
    protected function prepareIndexQuery(Doctrine_Query $query)
    {
        // Hook for prepare the index query
    }

    /**
     * @return void
     */
    protected function filerIndexQuery(Doctrine_Query $query)
    {
        // Hook for filter the index query
    }

    /**
     * @return void
     */
    protected function sortIndexQuery(Doctrine_Query $query)
    {
        $pattern = '/(asc|desc)ending_by_([a-z_]+)/';
        if (preg_match($pattern, $this->_getParam('sort', $this->_sortDefault), $matches)) {
            list($field, $order) = array($matches[2], $matches[1]);
            $query->orderBy("$field $order");
            $this->view->sortData = array('field' => $field, 'order' => $order);
        }
    }
    
    /**
     * @return Doctrine_Query
     */
    protected function getIndexQuery()
    {
        $query = $this->getTable()->createQuery();
        $this->prepareIndexQuery($query);
        $this->filerIndexQuery($query);
        $this->sortIndexQuery($query);
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
        $record = $this->getNewRecord();

        if ($this->_request->isPost()) {
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
        $record = $this->getRecord($this->_getParam('id'));

        if ($this->_request->isPost()) {
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
        $this->destroy($record);
        $this->_helper->redirector('index');
    }

    /**
     * Create
     */
    protected function create(Doctrine_Record $record)
    {
        $record->fromArray($this->_getParam('record'));
    }

    /**
     * Update
     */
    protected function update(Doctrine_Record $record)
    {
        $record->fromArray($this->_getParam('record'));
    }

    /**
     * Destroy
     */
    protected function destroy(Doctrine_Record $record)
    {
        $record->delete();
    }

    /**
     * Defines the current model
     *
     * @return GlassOnion_Controller_Crud_Doctrine Provides a fluent interface
     */
    protected function useModel($tableName)
    {
        if (!class_exists($tableName)) {
            /**
             * @see GlassOnion_Controller_Crud_Exception
             */
            require_once 'GlassOnion/Controller/Crud/Exception.php';
            throw new GlassOnion_Controller_Crud_Exception(
                'The class ' . $tableName . ' does not exists');
        }
        $this->_tableName = $tableName;
        return $this;
    }
    
    /**
     * Sets the item count per pate
     *
     * @return GlassOnion_Controller_Crud_Doctrine Provides a fluent interface
     */
    protected function setItemCountPerPage($count)
    {
        if (!is_integer($count) || $count < 1) {
            /**
             * @see GlassOnion_Controller_Crud_Exception
             */
            require_once 'GlassOnion/Controller/Crud/Exception.php';
            throw new GlassOnion_Controller_Crud_Exception(
                'The item count per page must be integer and positive');
        }
        $this->_itemCountPerPage = $count;
        return $this;
    }
    
    /**
     * Enables the one match redirect
     *
     * @return GlassOnion_Controller_Crud_Doctrine Provides a fluent interface
     */
    protected function enableOneMatchRedirect()
    {
        $this->_oneMatchRedirect = true;
        return $this;
    }

    /**
     * Disables the one match redirect
     *
     * @return GlassOnion_Controller_Crud_Doctrine Provides a fluent interface
     */
    protected function disableOneMatchRedirect()
    {
        $this->_oneMatchRedirect = false;
        return $this;
    }
    
    /**
     * Sets the sorting defaults
     *
     * @return GlassOnion_Controller_Crud_Doctrine Provides a fluent interface
     */
    public function sortDefaults($field, $order = 'asc')
    {
        $this->_sortDefault = $order . 'ending_by_' . $field;
        return $this;
    }
    
    /**
     * Proxy for undefined methods.  
     *
     * @param  string $methodName
     * @param  array $args
     * @return void
     * @throws Zend_Controller_Action_Exception
     */
    public function __call($methodName, $args)
    {
        $pattern = '/(getOrCreate|get)([a-z_]+)By([a-z_]+)/i';
        if (!preg_match($pattern, $methodName, $matches)) {
            return parent::__call($methodName, $args);
        }
        require_once 'Doctrine/Inflector.php';
        $fieldName = Doctrine_Inflector::tableize($matches[3]);
        return $this->getRecordBy($fieldName, $args, 'getOrCreate' === $matches[1], $matches[2]);
    }
    
    /**
     * Find a record or create a new one if not exists. Either case return the record.
     *
     * @param  string $fieldName
     * @param  string $value
     * @param  bool $createIfNotExists
     * @param  string $tableName
     * @return void
     * @throws Zend_Controller_Action_Exception
     */
    protected function getRecordBy($fieldName, $value, $createIfNotExists = false, $tableName = null)
    {
        $table = $this->getTable($tableName);
        $record = $table->findOneBy($fieldName, $value);
        if (!$record && $createIfNotExists) {
            $record = $table->create();
            $record->set($fieldName, $value);
        }
        return $record;
    }

    /**
     * Returns an existing record
     *
     * @return Doctrine_Record
     */
    protected function getRecord($id, $tableName = null)
    {
        $record = $this->getTable($tableName)->find($id);
        if (!$record) {
            /**
             * @see GlassOnion_Controller_Crud_Exception
             */
            require_once 'GlassOnion/Controller/Crud/Exception.php';
            throw new GlassOnion_Controller_Crud_Exception(
                'The record does not exists');
        }
        return $record;
    }

    /**
     * Returns a new record
     *
     * @return Doctrine_Record
     */
    protected function getNewRecord($tableName = null)
    {
        return $this->getTable($tableName)->create();
    }

    /**
     * Returns the current table
     *
     * @return Doctrine_Table
     */
    protected function getTable($tableName = null)
    {
        if (null === $tableName) {
            $tableName = $this->_tableName;
        }
        return Doctrine_Core::getTable($tableName);   
    }
    
    /**
     * Check if the query has filter
     *
     * @return boolean
     */
    private function _queryIsFiltered(Doctrine_Query $query)
    {
        $params = $query->getParams();
        return isset($params['where']) && count($params['where']) > 0;
    }
}
