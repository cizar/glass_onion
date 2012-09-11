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
    private $_modelClass = null;

    /**
     * The number of items per page
     *
     * @var integer
     */
    private $_itemCountPerPage = 10;
    
    /**
     * Auto-Redirect when listing only returns one match
     *
     * @var boolean
     */
    private $_oneMatchRedirect = true;
    
    /**
     * The default sort field
     *
     * @var string
     */
    private $_defaultSortField = null;

    /**
     * The default sort order (asc or desc)
     *
     * @var string
     */
    private $_defaultSortOrder = 'asc';

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
            
        if ($this->_oneMatchRedirect && 1 == $paginator->getTotalItemCount()) {
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
        if ($data = $this->_getSortData()) {
            list($field, $order) = $data;
            $query->orderBy("$field $order");
            $this->view->sortData = array('order' => $order, 'field' => $field);
        }
    }
    
    /**
     * @return array
     */
    private function _getSortData()
    {
        $pattern = '/(asc|desc)ending_by_([a-z_]+)/';
        if (preg_match($pattern, $this->_getParam('sort'), $matches)) {
            return array($matches[2], $matches[1]);
        }
        if ($this->_defaultSortField) {
            return array($this->_defaultSortField, $this->_defaultSortOrder);
        }
        return null;
    }

    /**
     * @return Doctrine_Query
     */
    protected function getIndexQuery()
    {
        $this->_assertModelIsDefined();
        
        $query = Doctrine_Query::create()
            ->from($this->_modelClass);
            
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
        $this->_assertMethodExists('create', 'Doctrine_Record $record');

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
        $this->_assertMethodExists('update', 'Doctrine_Record $record');

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

        $record->delete();

        if (method_exists($this, 'destroy')) {
            $this->destroy($record);
        }

        $this->_helper->redirector('index');
    }

    /**
     * Defines the current model
     *
     * @return GlassOnion_Controller_Crud_Doctrine Provides a fluent interface
     */
    protected function useModel($class)
    {
        if (!class_exists($class)) {
            /**
             * @see GlassOnion_Controller_Crud_Exception
             */
            require_once 'GlassOnion/Controller/Crud/Exception.php';
            throw new GlassOnion_Controller_Crud_Exception(
                'The model ' . $class . ' does not exists');
        }
        
        $this->_modelClass = $class;
        
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
    public function sortDefaults($field, $order = null)
    {
        $this->_defaultSortField = $field;
        if (null !== $order) {
            $this->_defaultSortOrder = $order;
        }
        return $this;
    }

    /**
     * Returns an existing record
     *
     * @return Doctrine_Record
     */
    protected function getRecord($id = null)
    {
        if (is_null($id)) {
            return $this->getNewRecord();
        }

        return $this->getTable()->find($id);
    }

    /**
     * Returns a new record
     *
     * @return Doctrine_Record
     */
    protected function getNewRecord()
    {
        $this->_assertModelIsDefined();
        
        $record = new $this->_modelClass;

        if (!is_subclass_of($record, 'Doctrine_Record')) {
            /**
             * @see GlassOnion_Controller_Crud_Exception
             */
            require_once 'GlassOnion/Controller/Crud/Exception.php';
            throw new GlassOnion_Controller_Crud_Exception(
                'The object is not a Doctrine_Record');
        }

        return $record;
    }

    /**
     * Returns the current table
     *
     * @return Doctrine_Table
     */
    protected function getTable()
    {
        $this->_assertModelIsDefined();
        return Doctrine_Core::getTable($this->_modelClass);   
    }

    /**
     * Verify that the assumption of the existence of the method is correct
     *
     * @return void
     */
    private function _assertModelIsDefined()
    {
        if (null === $this->_modelClass) {
            /**
             * @see GlassOnion_Controller_Crud_Exception
             */
            require_once 'GlassOnion/Controller/Crud/Exception.php';
            throw new GlassOnion_Controller_Crud_Exception(
                'No model has been defined');
        }
    }
    
    /**
     * @param string $method
     * @param string $params
     * @return void
     */
    private function _assertMethodExists($method, $params = null)
    {
        if (!method_exists($this, $method)) {
            $signature = get_class($this) . '::' . $method . '(' . $params . ')';
           
            /**
             * @see GlassOnion_Controller_Crud_Exception
             */
            require_once 'GlassOnion/Controller/Crud/Exception.php';
            throw new GlassOnion_Controller_Crud_Exception(
                "Method {$signature} must be implemented");
        }
    }
}
