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
 * @package GlassOnion_Mysqli
 */
abstract class GlassOnion_Mysqli_ResultSet_Abstract implements Iterator, Countable
{
    /**
     * Return the number of fields of the recordset
     */
    public abstract function getFieldCount();

    /**
     * Return the number of rows of the recordset
     */
    public abstract function getNumRows();

    /**
     * Fetch all rows as an array
     *
     * @return array
     */
    public function fetchAll()
    {
        $rows = array();

        foreach ($this as $row) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Fetch all rows as a key/value array
     *
     * @return array
     */
    public function toKeyValueArray()
    {
        $rows = array();

        foreach ($this as $row) {
            $vars = array_keys(get_object_vars($row));

            switch (count($vars)) {
                case 1:
                    $valueField = array_shift($vars);
                    $rows[] = $row->$valueField;
                    break;
                case 2:
                    $keyField = array_shift($vars);
                    $valueField = array_shift($vars);
                    $rows[$row->$keyField] = $row->$valueField;
                    break;
                default:
                    require_once 'GlassOnion/Mysqli/ResultSet/Exception.php';
                    throw new GlassOnion_Mysqli_ResultSet_Exception(
                        'The number of fields in result must be between 1 and 2');
            }
        }

        return $rows;
    }
}
