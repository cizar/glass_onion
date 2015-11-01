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
 * @category   GlassOnion
 * @package    GlassOnion_Doctrine
 */
class GlassOnion_Doctrine
{
  /**
   * @var string
   */
  const DEFAULT_MULTI_OPTION_FORMAT = '%value$s';

  /**
   * @var string
   */
  const DEFAULT_MULTI_OPTION_KEY = 'id';

  /**
   * TBD
   *
   * @param Doctrine_Query|Doctrine_Collection|string $source
   * @param string $format
   * @param string $key
   * @param string $firstOption
   * @return array
   * @throws InvalidArgumentException
   */
  public static function getMultiOptions($source, $format = null, $key = null, $blankOption = null)
  {
    if (null == $format) {
      $format = self::DEFAULT_MULTI_OPTION_FORMAT;
    }
    if (null == $key) {
      $key = self::DEFAULT_MULTI_OPTION_KEY;
    }
    if ($source instanceof Doctrine_Collection) {
      $records = $source;
    } else if ($source instanceof Doctrine_Query) {
      $records = $source->execute();
    } else if (is_string($source)) {
      if (!Doctrine_Core::isValidModelClass($source)) {
        throw new InvalidArgumentException('The class "' . $source . '" is not a valid model');
      }
      $records = Doctrine_Query::create()->from($source)->execute();
    } else {
      throw new InvalidArgumentException('Unknown source');
    }
    /**
     * @see GlassOnion_String
     */
    require_once 'GlassOnion/String.php';
    $options = $blankOption ? array('' => $blankOption) : array();
    foreach ($records->toArray() as $record) {
      $options[$record[$key]] = GlassOnion_String::vnsprintf($format, $record);
    }
    return $options;
  }

  /**
   * Find a record or create a new one if does not exists. Either case return the record.
   *
   * @param  string $tableName
   * @param  string $fieldName
   * @param  string $value
   *   or
   * @param  string $tableName
   * @param  array $fieldValueHashArray
   *
   * @return Doctrine_Record
   * @throws Zend_Controller_Action_Exception
   */
  public static function findOneOrCreate()
  {
    $args = func_get_args();
    $tableName = array_shift($args);
    if (is_array($args[0])) {
      $criteria = array_shift($args);
    } else {
      foreach (array_chunk($args, 2) as $pair) {
        list($key, $value) = $pair;
        $criteria[$key] = $value;
      }
    }
    $table = Doctrine_Core::getTable($tableName);
    $query = $table->createQuery()->limit(1);
    foreach ($criteria as $fieldName => $value) {
      $query->andWhere($fieldName . ' = ?', $value);
    }
    $record = $query->fetchOne();
    if (!$record) {
      $record = $table->create();
      foreach ($criteria as $fieldName => $value) {
        $record->set($fieldName, $value);
      }
    }
    return $record;
  }
}
