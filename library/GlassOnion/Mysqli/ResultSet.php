<?php

/**
 * @see GlassOnion_Mysqli_ResultSet_Abstract
 */
require_once 'GlassOnion/Mysqli/ResultSet/Abstract.php';

/**
 * @see GlassOnion_Mysqli_ResultSet_Exception
 */
require_once 'GlassOnion/Mysqli/ResultSet/Exception.php';

/**
 * @package GlassOnion_Mysqli
 */
class GlassOnion_Mysqli_ResultSet extends GlassOnion_Mysqli_ResultSet_Abstract
{
    /**
     * ResultSet row type
     * 
     * @var string
     */
    const TYPE_ARRAY = 'a';
    const TYPE_ASSOC = 's';
    const TYPE_BOTH = 'b';
    const TYPE_OBJECT = 'o';

    /**
     * @var resource
     */
    private $result;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $current;

    /**
     * @var integer
     */
    private $position;

    /**
     * Constructor
     */
    public function __construct(mysqli_result $result, $type = self::TYPE_OBJECT)
    {
        $this->result = $result;
        $this->setType($type);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->result->free();
    }

    /**
     * Return the current row
     *
     * @return mixed
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Move forward to next row
     * 
     * @return void
     */
    public function next()
    {
        $this->position++;
        $this->current = $this->fetch();
    }

    /**
     * Return the key of the current row
     *
     * @return scalar 
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean
     */
    public function valid()
    {
        return (bool) $this->current;
    }

    /**
     * Rewind the Iterator to the first row
     * 
     * @return void
     */
    public function rewind()
    {
        $this->result->data_seek($this->position = 0);
        $this->current = $this->fetch();
    }

    /**
     * Returns a single row from this resultset
     *
     * @return mixed
     */
    public function fetch()
    {
        switch ($this->type)
        {
            case self::TYPE_ARRAY:
                return $this->result->fetch_row();
            case self::TYPE_ASSOC:
                return $this->result->fetch_assoc();
            case self::TYPE_BOTH:
                return $this->result->fetch_array();
            case self::TYPE_OBJECT:
                return $this->result->fetch_object();
            default:
                throw new GlassOnion_Mysqli_Exception('Unknown result type');
        }
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
     * Return the number of fields
     * 
     * @return integer
     */
    public function getFieldCount()
    {
         return $this->result->field_count;
    }

    /**
     * Return the number of rows
     *
     * @return integer
     */
    public function getNumRows()
    {
        return $this->result->num_rows;
    }

    /**
     * Sets the result type
     *
     * @param string $type
     * @return GlassOnion_Mysqli_ResultSet
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
