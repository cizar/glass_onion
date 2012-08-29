<?php

/**
 * @see GlassOnion_Mysqli_ResultSet_PreparedStatement
 */
require_once 'GlassOnion/Mysqli/ResultSet/PreparedStatement.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Mysqli
 */
class GlassOnion_Mysqli_PreparedStatement
{
    /**
     * Statement type
     *
     * @var string
     */
    const STATEMENT_TYPE_INTEGER = 'i';
    const STATEMENT_TYPE_DOUBLE = 'd';
    const STATEMENT_TYPE_STRING = 's';
    const STATEMENT_TYPE_BLOB = 'b';

    /**
     * Prepared statement resource
     *
     * @var resource
     */
    private $stmt = NULL;

    /**
     * @var string
     */
    private $types = '';

    /**
     * @var array
     */
    private $params = array();

    /**
     * Constructor
     */
    public function __construct(mysqli_stmt $stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * Appends an integer value for binding
     */
    public function appendInteger($value)
    {
        return $this->appendParam(self::STATEMENT_TYPE_INTEGER, $value);
    }

    /**
     * Appends a double value for binding
     */
    public function appendDouble($value)
    {
        return $this->appendParam(self::STATEMENT_TYPE_DOUBLE, $value);
    }

    /**
     * Appends a string value for binding
     */
    public function appendString($value)
    {
        return $this->appendParam(self::STATEMENT_TYPE_STRING, $value);
    }

    /**
     * Appends a blob value for binding
     */
    public function appendBlob($value)
    {
        return $this->appendParam(self::STATEMENT_TYPE_BLOB, $value);
    }

    /**
     * Appends a parameter for binding
     */
    public function appendParam($type, $value)
    {
        $this->types .= $type;
        array_push($this->params, $value);
        return $this;
    }

    /**
     * Prepends a parameter for binding
     */
    public function prependParam($type, $value)
    {
        $this->types = $type . $this->types;
        array_unshift($this->params, $value);
        return $this;
    }

    /**
     * Setups the parameters for binding
     */
    public function setParams()
    {
        $args = func_get_args();
        $this->types = array_shift($args);
        $this->params = $args;
        return $this;
    }

    /**
     * Remove all parameters for binding
     */
    public function clearParams()
    {
        $this->types = '';
        $this->params = array();
        return $this;
    }

    /**
     * Executes the prepared query
     */
    public function execute()
    {
        if ($this->params) {
            call_user_func_array(array($this->stmt, 'bind_param'),
                array_merge(array($this->types), self::refValues($this->params)));
        }

        $this->stmt->execute();

        if ($this->stmt->errno) {
            throw new GlassOnion_Mysqli_Exception(
                $this->stmt->error, $this->stmt->errno);
        }

        /**
         * IMPORTANT: Detectar si la sentencia es de consulta o escritura.
         * 
         * Cuando se hace un SELECT en un prepared statement sin hacer
         * mysqli_stmt::store_result(), el affected_rows retorna en -1. Dado
         * que esta funcion solo es llamada por un metodo del resultado en
         * GlassOnion_Mysqli_ResultSet_PreparedStatement y que los errores fueron
         * detectados anteriormente, puedo asumir en este nivel que ese
         * valor corresponde a un SELECT.
         * 
         */
        if ($this->stmt->affected_rows == -1) {
            return new GlassOnion_Mysqli_ResultSet_PreparedStatement($this->stmt);
        }

        return NULL;
    }

    /**
     * Close the prepared statement
     */
    public function close()
    {
        $this->stmt->close();
    }

    /**
     * Function needed for mysqli_stmt::bind_param to work in PHP 5.3+
     * @see http://www.php.net/manual/en/mysqli-stmt.bind-param.php
     */
    private static function refValues($arr)
    { 
        // Reference is required for PHP 5.3+ 
        if (strnatcmp(phpversion(),'5.3') >= 0) { 
            $refs = array(); 
            foreach($arr as $key => $value) {
                $refs[$key] = &$arr[$key]; 
            }
            return $refs; 
        } 
        return $arr; 
    }
}
