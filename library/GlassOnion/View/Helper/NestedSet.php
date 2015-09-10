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
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_NestedSet extends Zend_View_Helper_Abstract
{
  private $_root;
  private $_iterator;
  private $_data;

  public function nestedSet($root, $iterator = null, $data = null)
  {
    $this->_root = $root;
    if (null !== $iterator) {
      $this->setIterator($iterator);
    }
    $this->_data = $data;
    return $this;
  }

  public function getIterator()
  {
    if (null == $this->_iterator) {
      return array($this, '_render');
    }
    return $this->_iterator;
  }

  public function setIterator($iterator)
  {
    if (!is_callable($iterator)) {
      throw new Exception('No es ejecutable');
    }
    $this->_iterator = $iterator;
    return $this;
  }

  public function getData()
  {
    return $this->_data;
  }

  public function setData($data)
  {
    $this->_data = $data;
    return $this;
  }

  private function _renderRoot($root)
  {
    if ($root instanceof Doctrine_Record) {
      $root = $root->getNode();
    }
    if ($root instanceof Doctrine_Tree_NestedSet) {
      $content = $this->_renderTree($root);
    } else if ($root instanceof Doctrine_Node_NestedSet) {
      $content = $this->_renderNode($root);
    } else {
      throw new Exception("La raíz debe ser una instancia de Doctrine_Tree_NestedSet o Doctrine_Node_NestedSet");
    }
    return "<ul>{$content}</ul>";
  }

  private function _renderTree(Doctrine_Tree_NestedSet $tree)
  {
    $roots = $tree->fetchRoots();
    return $this->_renderCollection($roots);
  }

  private function _renderNode(Doctrine_Node_NestedSet $node)
  {
    $content = $this->_renderRecord($node->getRecord());
    if ($node->hasChildren()) {
      $content .= $this->_renderChildren($node->getChildren());
    }
    return "<li>{$content}</li>";
  }

  private function _renderCollection(Doctrine_Collection $collection)
  {
    $content = '';
    foreach ($collection as $record) {
      $content .= $this->_renderNode($record->getNode());
    }
    return $content;
  }

  private function _renderChildren(Doctrine_Collection $children)
  {
    $content = $this->_renderCollection($children);
    return "<ul>{$content}</ul>";
  }

  private function _renderRecord(Doctrine_Record $record)
  {
    return call_user_func($this->getIterator(), $record, $this->getData());
  }

  public function _render(Doctrine_Record $record)
  {
    return (string) $record;   
  }

  public function __toString()
  {
    try {
      return $this->_renderRoot($this->_root);
    } catch (Exception $ex) {
      return $ex->getMessage();
    }
  }
}