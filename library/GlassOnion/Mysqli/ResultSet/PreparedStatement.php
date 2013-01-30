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
 * @see GlassOnion_Mysqli_ResultSet_Abstract
 */
require_once 'GlassOnion/Mysqli/ResultSet/Abstract.php';

/**
 * @package GlassOnion_Mysqli
 */
class GlassOnion_Mysqli_ResultSet_PreparedStatement
    extends GlassOnion_Mysqli_ResultSet_Abstract
{
    /**
     * Prepared statement resource
     */
    private $stmt;

    /**
     * @var mixed
     */
    private $current;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var mixed
     */
    private $dummy;

    /**
     * @var boolean
     */
    private $stored;

    /**
     * Constructor
     */
    public function __construct(mysqli_stmt $stmt)
    {
        $this->stmt = $stmt;
        $this->bindResultToDummyObject();
        $this->stored = FALSE;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->stmt->free_result();
    }

    /**
     * Return the current element
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        $this->position++;
        $this->current = $this->fetch();
    }

    /**
     * Return the key of the current element
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     */
    public function valid()
    {
        return (bool) $this->current;
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        $this->stmt->data_seek($position = 0);
        $this->current = $this->fetch();
    }

    /**
     * Returns a single row from this resultset
     */
    public function fetch()
    {
        return $this->stmt->fetch() ? clone $this->dummy : NULL;
    }

    /**
     * Close the prepared statement
     *
     * @todo Check responsability of close the statement!
     *
     * It goes here in the resultset or in the statement?
     */
    public function close()
    {
        $this->stmt->close();
    }

    /**
     * Returns the number of rows
     *
     * @return integer
     */
    public function count()
    {
        return $this->getNumRows();
    }

    /**
     * Return the number of fields of the recordset
     */
    public function getFieldCount()
    {
         return $this->stmt->field_count;
    }

    /**
     * Return the number of rows
     * 
     * The use of mysqli_stmt_num_rows() depends on whether or not you used
     * mysqli_stmt_store_result() to buffer the entire result set in the
     * statement handle.
     * 
     * If you use mysqli_stmt_store_result(), mysqli_stmt_num_rows() may be
     * called immediately.
     *
     * @return integer
     */
    public function getNumRows()
    {
        if (!$this->stored) {
            require_once 'GlassOnion/Mysqli/ResultSet/Exception.php';
            throw new GlassOnion_Mysqli_ResultSet_Exception(
                'GlassOnion_Mysqli_ResultSet_PreparedStatement::store() method'
                . ' must be called first');
        }

        return $this->stmt->num_rows;
    }

    /**
     * Transfers a result set from a prepared statement
     */
    public function store()
    {
        $this->stored = true;
        $this->stmt->store_result();
        return $this;
    }

    /**
     * Bind the result to the dummy object
     */
    private function bindResultToDummyObject()
    {
        $this->dummy = new stdClass();

        $metadata = $this->stmt->result_metadata();

        $refs = array();

        while ($field = $metadata->fetch_field()) {
            $refs[] = &$this->dummy->{$field->name};
        }

        call_user_func_array(array($this->stmt, 'bind_result'), $refs);
    }
}
