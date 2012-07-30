<?php

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
