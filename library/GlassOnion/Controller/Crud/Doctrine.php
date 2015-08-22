<?php

/**
 * Glass Onion
 *
 * Copyright (c) 2009 César Kästli (cesarkastli@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the
 * Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @copyright  Copyright (c) 2009 César Kästli (cesarkastli@gmail.com)
 * @license    MIT
 */

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
  private $_oneMatchRedirect = false;

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

    $this->filerIndexQuery($query);

    $this->sortIndexQuery($query);

    $page = $this->_getParam('page', 1);

    $paginator = $this->getPaginator($query, $page, $this->_itemCountPerPage);

    if ($this->_oneMatchRedirect && $query->contains('WHERE') && 1 == $paginator->getTotalItemCount()) {
      $this->_helper->redirector('show', null, null, array('id' => $id = $paginator->getItem(1)->id));
    }

    $this->view->records = $paginator;
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
    $pattern = '/(asc|desc)ending-by-([a-z_]+)/';
    if (preg_match($pattern, $this->_getParam('sort', $this->getSortDefault()), $matches)) {
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
    return $this->getTable()->createQuery();
  }

  /**
   * @return void
   */
  public function showAction()
  {
    $query = $this->getIndexQuery();
    $record = $query->andWhere('id = ?', $this->_getParam('id'))->fetchOne();
    if (!$record) {
      /**
       * @see GlassOnion_Controller_Crud_Exception
       */
      require_once 'GlassOnion/Controller/Crud/Exception.php';
      throw new GlassOnion_Controller_Crud_Exception(
        'The record does not exists');
    }
    $this->record = $record;
    $this->view->record = $record;
  }

  /**
   * @return void
   */
  public function newAction()
  {
    $record = $this->createRecord();
    $this->view->record = $this->record = $record;

    if ($this->_request->isPost()) {
      try {
        $this->beginTransaction();
        $this->create($record);
        $record->save();
        $this->postCreate($record);
        $this->commit();
        $this->_helper->flashMessenger->success($this->getCreateSuccessMessage($record));
        $this->_helper->redirect('index');
      }
      catch (Doctrine_Validator_Exception $ex) {
        $this->rollback();
        $this->_helper->flashMessenger->error($this->getCreateErrorMessage($ex));
        $this->view->invalidRecords = $ex->getInvalidRecords();
      }
    }
  }

  /**
   * @retrurn string
   */
  public function getCreateSuccessMessage(Doctrine_Record $record)
  {
    return 'Se ha creado el registro';
  }

  /**
   * @retrurn string
   */
  public function getCreateErrorMessage(Doctrine_Validator_Exception $ex)
  {
    return 'Se han encontrado errores, verifique los datos ingresados y vuelva a intentar';
  }

  /**
   * @return void
   */
  public function editAction()
  {
    $record = $this->getRecord($this->_getParam('id'));
    $this->view->record = $this->record = $record;

    if ($this->_request->isPost()) {
      try {
        $this->beginTransaction();
        $this->update($record);
        $record->save();
        $this->postUpdate($record);
        $this->commit();
        $this->_helper->flashMessenger->success($this->getUpdateSuccessMessage($record));
        $this->_helper->redirect('index');
      }
      catch (Doctrine_Validator_Exception $ex) {
        $this->rollback();
        $this->_helper->flashMessenger->error($this->getUpdateErrorMessage($ex));
        $this->view->invalidRecords = $ex->getInvalidRecords();
      }
    }
  }

  /**
   * @return void
   */
  public function getUpdateSuccessMessage(Doctrine_Record $record)
  {
    return 'Se ha actualizado el registro';
  }

  /**
   * @retrurn string
   */
  public function getUpdateErrorMessage(Doctrine_Validator_Exception $ex)
  {
    return 'Se han encontrado errores, verifique los datos ingresados y vuelva a intentar';
  }

  /**
   * @return void
   */
  public function deleteAction()
  {
    $record = $this->getRecord($this->_getParam('id'));
    $this->view->record = $this->record = $record;

    if ($this->_request->isPost()) {
      try {
        $this->delete($record);
        $this->_helper->flashMessenger->success($this->getDeleteSuccessMessage($record));
        $this->_helper->redirect('index');
      } catch (Doctrine_Connection_Mysql_Exception $ex) {
        switch ($ex->getCode()) {
          case 23000:
            $this->_helper->flashMessenger->error($ex->getMessage());
            break;
          default:
            $this->_helper->flashMessenger->error($this->getDeleteErrorMessage($ex));
        }
      }
    }
  }

  /**
   * @retrurn string
   */
  public function getDeleteSuccessMessage(Doctrine_Record $record)
  {
    return 'Se ha eliminado el registro';
  }

  /**
   * @retrurn string
   */
  public function getDeleteErrorMessage(Doctrine_Validator_Exception $ex)
  {
    return 'Se han encontrado errores, verifique los datos ingresados y vuelva a intentar';
  }

  /**
   * Create
   */
  protected function create(Doctrine_Record $record)
  {
    $record->fromArray($this->getParam('record'));
  }

  /**
   * Post Create
   */
  protected function postCreate(Doctrine_Record $record)
  {
    // Hook for the post create
  }

  /**
   * Update
   */
  protected function update(Doctrine_Record $record)
  {
    $record->fromArray($this->getParam('record'));
  }

  /**
   * Post Update
   */
  protected function postUpdate(Doctrine_Record $record)
  {
    // Hook for the post update
  }

  /**
   * Delete
   */
  protected function delete(Doctrine_Record $record)
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
        "The class {$tableName} does not exists");
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
   * Returns the default sorting value
   *
   * @return string
   */
  protected function getSortDefault()
  {
    if (null == $this->_sortDefault) {
      $table = $this->getTable();
      // TODO: parse orderBy like "fieldName ASC|DESC"
      $this->_sortDefault = 'ascending-by-' . $table->orderBy;
    }
    return $this->_sortDefault;
  }

  /**
   * Sets the sorting defaults
   *
   * @return GlassOnion_Controller_Crud_Doctrine Provides a fluent interface
   */
  public function sortDefaults($fieldName, $order = 'asc')
  {
    $this->_sortDefault = $order . 'ending-by-' . $fieldName;
    return $this;
  }

  /**
   * Proxy for undefined methods.
   *
   * @param  string $methodName
   * @param  array $args
   * @return void
   */
  public function __call($methodName, $args)
  {
    $pattern = '/^getMaxValueOf([a-z_]+)From([a-z_]+)/i';
    if (preg_match($pattern, $methodName, $matches)) {
      require_once 'Doctrine/Inflector.php';
      $fieldName = Doctrine_Inflector::tableize($matches[1]);
      return $this->getMaxValueOf($fieldName, $matches[2]);
    }

    $pattern = '/^getMultiOptionsFrom([a-z_]+)/i';
    if (preg_match($pattern, $methodName, $matches)) {
      return call_user_func_array(array($this, 'getMultiOptions'),
        array_merge(array($matches[1]), $args));
    }

    $pattern = '/^(getOrCreate|get)([a-z_]+)By([a-z_]+)/i';
    if (preg_match($pattern, $methodName, $matches)) {
      require_once 'Doctrine/Inflector.php';
      $fieldName = Doctrine_Inflector::tableize($matches[3]);
      return $this->getRecordBy($fieldName, $args[0],
        $matches[2], 'getOrCreate' === $matches[1]);
    }

    $pattern = '/^get([a-z_]+)/i';
    if (preg_match($pattern, $methodName, $matches)) {
      return $this->getRecord($args[0], $matches[1]);
    }

    return parent::__call($methodName, $args);
  }

  /**
   * TBD
   *
   * @param Doctrine_Query|Doctrine_Collection|string $source
   * @param string $format
   * @param string $key
   * @return array
   * @throws InvalidArgumentException
   */
  public function getMultiOptions($source, $format = '%value$s', $key = 'id')
  {
    /**
     * @see GlassOnion_Doctrine
     */
    require_once 'GlassOnion/Doctrine.php';
    return GlassOnion_Doctrine::getMultiOptions($source, $format, $key);
  }

  /**
   * Returns the max value of a field
   *
   * @param  string $tableName
   * @param  string $fieldName
   * @return integer
   */
  public function getMaxValueOf($fieldName, $tableName)
  {
    return (int) Doctrine_Query::create()
      ->from($tableName)
      ->andWhere($fieldName . ' IS NOT NULL')
      ->orderBy($fieldName . ' DESC')
      ->limit(1)
      ->fetchOne()
      ->get($fieldName);
  }

  /**
   * Find a record or create a new one if not exists. Either case return the record.
   *
   * @param  string $fieldName
   * @param  string $value
   * @param  string $tableName
   * @param  bool $createIfNotExists
   *   or
   * @param  array $fieldValueArray
   * @param  string $tableName
   * @param  bool $createIfNotExists
   *
   * @return Doctrine_Record
   * @throws Zend_Controller_Action_Exception
   */
  protected function getRecordBy()
  {
    $args = func_get_args();
    if (0 == count($args) || 1 == count($args) && !is_array($args[0]) || 4 < count($args)) {
      throw new InvalidArgumentException();
    }
    $criteria = is_array($args[0]) ? array_shift($args) : array(array_shift($args) => array_shift($args));
    $tableName = empty($args) ? null : array_shift($args);
    $createIfNotExists = empty($args) ? false : array_shift($args);
    $table = $this->getTable($tableName);
    $query = $table->createQuery()->limit(1);
    foreach ($criteria as $fieldName => $value) {
      $query->andWhere($fieldName . ' = ?', (array) $value);
    }
    $record = $query->fetchOne();
    if (!$record && $createIfNotExists) {
      $record = $table->create();
      foreach ($criteria as $fieldName => $value) {
        $record->set($fieldName, $value);
      }
    }
    if (!$record) {
      /**
       * @see GlassOnion_Controller_Crud_Exception
       */
      require_once 'GlassOnion/Controller/Crud/Exception.php';
      throw new GlassOnion_Controller_Crud_Exception(
        "A record of $tableName with $fieldName '$value' could not be found");
    }
    return $record;
  }

  /**
   * Returns an existing record
   *
   * @return Doctrine_Record
   * @throws Zend_Controller_Action_Exception
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
  protected function createRecord($tableName = null)
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
   * Returns a paginator from a given query
   *
   * @return Zend_Paginator
   */
  protected function getPaginator(Doctrine_Query $query, $pageNumber = null, $itemCountPerPage = null)
  {
    require_once 'GlassOnion/Paginator.php';
    $paginator = GlassOnion_Paginator::factory($query);
    if (null != $pageNumber) {
      $paginator->setCurrentPageNumber($pageNumber);
    }
    if (null != $itemCountPerPage) {
      $paginator->setItemCountPerPage($itemCountPerPage);
    }
    return $paginator;
  }

  /**
   * Start a transaction
   *
   * @return integer
   */
  protected function beginTransaction()
  {
    return $this->getConnection()->beginTransaction();
  }

  /**
   * Commit the database changes done during a transaction
   *
   * @return boolean
   */
  protected function commit()
  {
    return $this->getConnection()->commit();
  }

  /**
   * Cancel any database changes done during a transaction
   *
   * @return boolean
   */
  protected function rollback()
  {
    return $this->getConnection()->rollback();
  }

  /**
   * Returns the current Doctrine connection
   *
   * @return Doctrine_Connection
   */
  protected function getConnection()
  {
    return Doctrine_Manager::connection();
  }
}
