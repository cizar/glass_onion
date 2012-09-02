<?php

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Auth
 */
class GlassOnion_Auth_Adapter_Doctrine
    implements Zend_Auth_Adapter_Interface
{
    /**
     * Doctrine connection
     *
     * @var Doctrine_Connection
     * @access private
     */
    private $_connection = null;

    /**
     * The table name to check
     *
     * @var string
     * @access private
     */
    private $_tableName = null;
    
    /**
     * The name of the column to be used as the identity
     *
     * @var string
     * @access private
     */
    private $_identityColumnName = null;

    /**
     * The name of the column to be used as the credential
     *
     * @var string
     * @access private
     */
    private $_credentialColumnName = null;

    /**
     * Treatment applied to the credential, such as MD5() or PASSWORD()
     *
     * @var string
     * @access private
     */
    private $_credentialTreatment = null;

    /**
     * Identity value
     *
     * @var string
     * @access private
     */
    private $_identity = null;

    /**
     * Credential value
     *
     * @var string
     * @access private
     */
    private $_credential = null;

    /**
     * Results of database authentication query
     *
     * @var string
     * @access private
     */
    private $_resultRow = null;

    /**
     * __construct() - Sets configuration options
     *
     * @param Doctrine_Connection $connection
     * @param string $tableName
     * @param string $identityColumnName
     * @param string $credentialColumnName
     * @param string $credentialTreatment
     * @return void
     */
    public function __construct(Doctrine_Connection $connection = null, $tableName = null,
                                $identityColumnName = null, $credentialColumnName = null,
                                $credentialTreatment = null)
    {
        if (null !== $connection) {
            $this->setConnection($connection);
        }

        if (null !== $tableName) {
            $this->setTableName($tableName);
        }

        if (null !== $identityColumnName) {
            $this->setIdentityColumnName($identityColumnName);
        }

        if (null !== $credentialColumnName) {
            $this->setCredentialColumnName($credentialColumnName);
        }

        if (null !== $credentialTreatment) {
            $this->setCredentialTreatment($credentialTreatment);
        }
    }

    /**
     * setConnection() - set the connection to the database
     * 
     * @return GlassOnion_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setConnection(Doctrine_Connection $connection)
    {
        $this->_connection = $connection;
        return $this;
    }

    /**
     * getConnection() - get the connection to the database
     * 
     * @return Doctrine_Connection
     */
    public function getConnection()
    {
        if (null === $this->_connection && null !== $this->_tableName) {
            $this->_connection = Doctrine_Core::getConnectionByTableName($this->_tableName);
        }
        
        return $this->_connection;
    }

    /**
     * setTableName() - set the table name to be used in the select query
     * 
     * @param string $tableName
     * @return GlassOnion_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
        return $this;
    }

    /**
     * setIdentityColumnName() - set the column name to be used as the identity column
     *
     * @param string $identityColumnName
     * @return GlassOnion_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setIdentityColumnName($identityColumnName)
    {
        $this->_identityColumnName = $identityColumnName;
        return $this;
    }

    /**
     * setCredentialColumnName() - set the column name to be used as the credential column
     *
     * @param string $credentialColumnName
     * @return GlassOnion_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setCredentialColumnName($credentialColumnName)
    {
        $this->_credentialColumnName = $credentialColumnName;
        return $this;
    }

    /**
     * setCredentialTreatment() - allows the developer to pass a parameterized string that is
     * used to transform or treat the input credential data.
     *
     * In many cases, passwords and other sensitive data are encrypted, hashed, encoded,
     * obscured, or otherwise treated through some function or algorithm. By specifying a
     * parameterized treatment string with this method, a developer may apply arbitrary SQL
     * upon input credential data.
     *
     * Examples:
     *
     *  'PASSWORD(?)'
     *  'MD5(?)'
     *
     * @param string $credentialTreatment
     * @return GlassOnion_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setCredentialTreatment($credentialTreatment)
    {
        $this->_credentialTreatment = $credentialTreatment;
        return $this;
    }

    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param string $identity
     * @return GlassOnion_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setIdentity($identity)
    {
        $this->_identity = $identity;
        return $this;
    }

    /**
     * setCredential() - set the value to be used as the credential
     *
     * @param string $credential
     * @return GlassOnion_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }

    /**
     * getResultRowObject() - Returns the result row as a stdClass object
     *
     * @return stdClass|false
     */
    function getResultRowObject()
    {
        if (!$this->_resultRow) {
            return false;
        }

        $returnObject = new stdClass();

        foreach ($this->_resultRow as $resultColumn => $resultValue) {
            $returnObject->{$resultColumn} = $resultValue;
        }

        return $returnObject;
    }

    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to 
     * attempt an authentication.  Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $identities = $this->_executeAuthenticationQuery();

        if (count($identities) < 1) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
               null, array('A record with the supplied identity could not be found.'));
        }
        
        if (count($identities) > 1) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS,
                null, array('More than one record matches the supplied identity.'));
        }
        
        if ($identities[0]['zend_auth_credential_match'] == 0) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                null, array('Supplied credential is invalid.'));
        }

        $this->_resultRow = $identities[0];

        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,
            $this->_identity, array('Authentication sucessful.'));
    }

    /**
     * _executeAuthenticationQuery() - This method executes the authentication query
     * against the database and returns the identities that matching identities
     *
     * @throws Zend_Auth_Adapter_Exception - when an invalid select object is encountered
     * @return array
     */
    private function _executeAuthenticationQuery()
    {
        try
        {
            return $this->_getAuthenticationQuery()->fetchArray();
        }
        catch (Exception $ex)
        {
            /**
             * @see Zend_Auth_Adapter_Exception
             */
            require_once 'Zend/Auth/Adapter/Exception.php';
            throw new Zend_Auth_Adapter_Exception('The supplied parameters to' 
                . ' GlassOnion_Auth_Adapter_Doctrine failed to produce a valid sql statement,'
                . ' please check table and column names for validity.', 0, $e);
        }
    }
    
    /**
     * _getAuthenticationQuery() - This method creates a Doctrine_Query object that
     * is completely configured to be queried against the database.
     *
     * @return Doctrine_Query
     */
    private function _getAuthenticationQuery()
    {
        // build credential expression
        if (empty($this->_credentialTreatment) || (strpos($this->_credentialTreatment, '?') === false)) {
            $this->_credentialTreatment = '?';
        }
        
        $credentialExpression = sprintf('(CASE WHEN %s = %s THEN 1 ELSE 0 END) AS %s',
            $this->_credentialColumnName,
            str_replace('?',
                $this->getConnection()->quote($this->_credential),
                $this->_credentialTreatment),
            'zend_auth_credential_match'
        );
        
        return Doctrine_Query::create($this->getConnection())
            ->select('*, ' . $credentialExpression)
            ->from($this->_tableName)
            ->where($this->_identityColumnName . ' = ?', $this->_identity);
    }
}
