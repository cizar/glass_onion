<?php

/**
 * @see GlassOnion_Mysqli_Table
 */
require_once 'GlassOnion/Mysqli/Table.php';

/**
 * @see GlassOnion_Mysqli_ResultSet
 */
require_once 'GlassOnion/Mysqli/ResultSet.php';

/**
 * @see GlassOnion_Mysqli_PreparedStatement
 */
require_once 'GlassOnion/Mysqli/PreparedStatement.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Mysqli
 */
class GlassOnion_Mysqli
{
    /**
     * @var mysqli
     */
    private $link;

    /**
     * Constructor
     * 
     * @param string|Zend_Config $hostOrConfig
     * @param string $user
     * @param string $pass
     * @param string $name
     * @throws GlassOnion_Mysqli_Exception
     */
    public function __construct($hostOrConfig = NULL, $user = NULL, $pass = NULL, $name = NULL)
    {
        if ($hostOrConfig instanceof Zend_Config) {
            $host = $hostOrConfig->host;
            $user = $hostOrConfig->user;
            $pass = $hostOrConfig->pass;
            $name = $hostOrConfig->name;
        }
        else {
            $host = $hostOrConfig;
        }

        $link = @new mysqli($host, $user, $pass, $name);

        if ($link->connect_errno) {
            require_once 'GlassOnion/Mysqli/Exception.php';
            throw new GlassOnion_Mysqli_Exception($link->connect_error, $link->connect_errno);
        }

        $this->link = $link;
    }

    /**
     * Selects the default database for database queries
     * 
     * @param string $name
     * @throws GlassOnion_Mysqli_Exception
     * @return GlassOnion_Mysqli Provides a fluent interface
     */
    public function selectDatabase($name)
    {
        $success = $this->link->select_db($name);

        if (!$success) {
            require_once 'GlassOnion/Mysqli/Exception.php';
            throw new GlassOnion_Mysqli_Exception($this->link->error, $this->link->errno);
        }

        return $this;
    }

    /**
     * Performs the query on the database
     * 
     * @param string $sql
     * @throws GlassOnion_Mysqli_Exception
     * @return GlassOnion_Mysqli_ResultSet
     */
    public function query($sql, $resultSetType = GlassOnion_Mysqli_ResultSet::TYPE_OBJECT)
    {
        $result = $this->link->query($sql);

        if ($this->link->errno) {
            require_once 'GlassOnion/Mysqli/Exception.php';
            throw new GlassOnion_Mysqli_Exception($this->link->error, $this->link->errno);
        }

        if (is_bool($result)) {
            return $result;
        }

        return new GlassOnion_Mysqli_ResultSet($result, $resultSetType);
    }

    /**
     * Prepares the query and returns a statement for further operations
     *
     * @param string $sql
     * @throws GlassOnion_Mysqli_Exception
     * @return GlassOnion_Mysqli_PreparedStatement
     */
    public function prepare($sql)
    {
        $stmt = $this->link->prepare($sql);

        if (!$stmt) {
            require_once 'GlassOnion/Mysqli/Exception.php';
            throw new GlassOnion_Mysqli_Exception(
                $this->link->error, $this->link->errno);
        }

        return new GlassOnion_Mysqli_PreparedStatement($stmt);
    }

    /**
     * Begin a transaction
     *
     * @return GlassOnion_Mysqli Provides a fluent interface
     */
    public function begin()
    {
        $this->link->autocommit(false);
        return $this;
    }

    /**
     * Commit a transaction
     *
     * @return GlassOnion_Mysqli Provides a fluent interface
     */
    public function commit()
    {
        $this->link->commit();
        $this->link->autocommit(true);
        return $this;
    }

    /**
     * Roll-back a transaction
     * 
     * @return GlassOnion_Mysqli Provides a fluent interface
     */
    public function rollback()
    {
        $this->link->rollback();
        $this->link->autocommit(true);
        return $this;
    }

    /**
     * Returns the non-TEMPORARY tables in a given databas
     * 
     * @throws GlassOnion_Mysqli_Exception
     * @return mixed
     */
    public function getTables($like = '%')
    {
        if (!is_string($like) || !preg_match('/^[a-z%][a-z0-9_%]*$/i', $like)) {
            require_once 'GlassOnion/Mysqli/Exception.php';
            throw new GlassOnion_Mysqli_Exception('Illegal LIKE pattern');
        }

        $result = $this->query("SHOW TABLES LIKE '$like'")
            ->setType(GlassOnion_Mysqli_ResultSet::TYPE_ARRAY);

        $tables = array();

        foreach ($result as $row) {
            $tables[] = $this->getTable($row[0]);
        }

        return $tables; 
    }

    /**
     * Returns a table
     * 
     * @return GlassOnion_Mysqli_Table
     */
    public function getTable($name)
    {
        return new GlassOnion_Mysqli_Table($name, $this);
    }

    /**
     * Returns true if the given table exists
     * 
     * @return mixed
     */
    public function tableExists($name)
    {
        return count($this->getTables($name)) == 1;
    }

    /**
     * Returns the last insert ID
     *
     * @return integer
     */
    public function getInsertId()
    {
        return $this->link->insert_id;
    }

    /**
     * Returns the number of affected rows
     *
     * @return integer
     */
    public function getAffectedRows()
    {
        return $this->link->affected_rows;
    }
}
