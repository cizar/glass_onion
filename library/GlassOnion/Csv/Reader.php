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
* @package    GlassOnion_Csv
*/
class GlassOnion_Csv_Reader implements Iterator, Countable
{
  /**
   * The file heading mode
   */
  const MODE_NO_HEADER = 0;
  const MODE_WITH_HEADER = 1;
  const MODE_IGNORE_HEADER = 2;

  /**
   * The default field delimiter character.
   */
  const DEFAULT_DELIMITER = ';';
  
  /**
   * The default field delimiter character.
   */
  const DEFAULT_ENCLOSURE = '"';

  /**
   * Must be greater than the longest line (in characters) to be found
   * in the CSV file (allowing for trailing line-end characters).
   */
  const DEFAULT_LENGTH = 4096;

  /**
   * The CSV file handler.
   *
   * @var resource
   * @access private
   */
  private $_handler = null;

  /**
   * The element that will be returned on each iteration.
   *
   * @var mixed
   * @access private
   */
  private $_current = null;

  /**
   * The current row position in the opened CSV file.
   *
   * @var integer
   * @access private
   */
  private $_key = null;

  /**
   * Specifies how to read and use the header.
   *
   * @var bool
   * @access private
   */
  private $_mode = null;

  /**
   * The field delimiter character of each line.
   *
   * @var string
   * @access private
   */
  private $_delimiter = null;

  /**
   * The enclosure character of each field.
   *
   * @var string
   * @access private
   */
  private $_enclosure = null;

  /**
   * The length of the reader buffer.
   *
   * @var integer
   * @access private
   */
  private $_length = null;
 
  /**
   * The names of the columns.
   *
   * @var array
   * @access private
   */
  private $_columnNames = null;

  /**
   * The constructor. It try to open the CSV file.
   *
   * @access public
   * @param string $filename The fullpath of the CSV file.
   * @param string $dialect The internal format of the CSV file.
   * @param integer $length The amount of bytes to be read on each iteration.
   * @throws Exception
   * @return void
   */
  public function __construct($filename, $mode = null, $delimiter = null, $enclosure = null, $length = null)
  {
    $this->_mode = $mode ?: self::NO_HEADER;
    $this->_delimiter = $delimiter ?: self::DEFAULT_DELIMITER;
    $this->_enclosure = $enclosure ?: self::DEFAULT_ENCLOSURE;
    $this->_length = $length ?: self::DEFAULT_LENGTH;
    $this->_open($filename);
  }

  /**
   * The destructor. It close the CSV file.
   *
   * @access public
   * @return void
   */
  public function __destruct()
  {
    $this->_close();
  }

  /**
   * Returns the names of the columns.
   *
   * @access public
   * @return array
   */
  public function getColumnNames()
  {
    return $this->_columnNames;
  }
  
  /**
   * Sets the names of the columns.
   *
   * @access public
   * @param array $columnNames
   * @return GlassOnion_Csv_Reader Provides a fluent interface
   */
  public function setColumnNames($columnNames)
  {
    $this->_columnNames = $columnNames;
    return $this;
  }

  /**
   * Returns the current CSV row data.
   *
   * @access public
   * @return array
   */
  public function current()
  {
    $current = $this->_current;
    if (isset($this->_columnNames)) {
      foreach ($this->_columnNames as $i => $name) {
        if (empty($name)) {
          continue;
        }
        $current[$name] = $current[$i];
      }
    }
    return $current;
  }

  /**
   * Returns the current row number.
   *
   * @access public
   * @return integer
   */
  public function key()
  {
    return $this->_key;
  }
  
  /**
   * Move the file pointer to the next row.
   *
   * @access public
   * @return void
   */
  public function next()
  {
    $this->_key++;
    $this->_current = $this->_read();
  }

  /**
   * Reset the file handler.
   *
   * @access public
   * @return void
   */
  public function rewind()
  {
    $this->_key = 0;
    rewind($this->_handler);
    if (in_array($this->_mode, array(self::MODE_WITH_HEADER, self::MODE_IGNORE_HEADER))) {
      $firstRow = $this->_read();
      if (self::MODE_WITH_HEADER == $this->_mode && !$this->_columnNames) {
        $this->_columnNames = $firstRow;
      }
    }
    $this->_current = $this->_read();
  }

  /**
   * Checks if the current row is readable.
   *
   * @access public
   * @return boolean
   */
  public function valid()
  {
    return !feof($this->_handler);
  }

  /**
   * Returns the number of rows in the CSV file.
   *
   * @access public
   * @return integer
   */
  public function count()
  {
    return iterator_count($this);
  }

  /**
   * Open a CSV file.
   *
   * @access private
   * @param string $filename The fullpath of the CSV file.
   * @throws Exception
   * @return void
   */
  private function _open($filename)
  {
    $fileHandler = @fopen($filename, 'r');
    if (!$fileHandler) {
      require_once 'GlassOnion/Csv/Exception.php';
      throw new GlassOnion_Csv_Exception("The file '$filename' could not be opened");
    }
    $this->_handler = $fileHandler;
    return $this;
  }
  
  /**
   * Close the CSV file.
   *
   * @access private
   * @return void
   */
  private function _close()
  {
    if ($this->_handler) {
      fclose($this->_handler);
    }
    return $this;
  }
 
  /**
   * Read the next row of the CSV file.
   *
   * @access private
   * @return array
   */
  private function _read()
  {
    return fgetcsv($this->_handler, $this->_length, $this->_delimiter, $this->_enclosure);
  }
}