<?php

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
