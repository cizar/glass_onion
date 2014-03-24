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
 * @package    GlassOnion_Mysqli
 */
class GlassOnion_Mysqli_Table
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var GlassOnion_Mysqli
     */
    private $db;

    /**
     * Constructor
     * 
     * @param string $name
     * @param GlassOnion_Mysqli $db
     */
    public function __construct($name, GlassOnion_Mysqli $db)
    {
        $this->name = $name;
        $this->db = $db;
    }

    /**
     * Returns the field information of the table
     * 
     * @return mixed
     */
    public function getFields()
    {
        $fields = array();

        $result = $this->db->query("DESCRIBE {$this->name}");

        foreach ($result as $data) {
            $fields[$data->Field] = $data;
        }

        return $fields;
    }

    /**
     * Returns the index information of the table
     * 
     * @return mixed
     */
    public function getIndexes()
    {
        $indexes = array();

        $result = $this->db->query("SHOW INDEXES FROM {$this->name}");

        foreach ($result as $data) {
            $indexes[$data->Key_name] = $data;
        }

        return $indexes;
    }

    /**
     * Returns the table information
     * 
     * @return mixed
     */
    public function getProperties()
    {
        return $this->db->query("SHOW TABLE STATUS LIKE '{$this->name}'")->fetch();
    }

    /**
     * Returns the string representation
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}