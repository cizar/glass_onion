<?php

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Auth
 */
class GlassOnion_Auth_Adapter_SingleUser implements Zend_Auth_Adapter_Interface
{
    /**
     * @var string
     * @access private
     */
    private static $_username = 'admin';

    /**
     * @var string
     * @access private
     */
    private static $_password = 'password';

    /**
     * @var string
     * @access private
     */
    private $_identity;

    /**
     * @var string
     * @access private
     */
    private $_credential;

    /**
     * This method sets the username and password expected
     */
    public static function configure($username, $password = null)
    {
        if (is_array($username))
        {
            self::$_username = $username['username'];
            self::$_password = $username['password'];
        }
        else
        {
            self::$_username = $username;
            self::$_password = $password;
        }
    }

    /**
     * This method sets the identity
     *
     * @param string
     * @return InAdvant_Auth_Adapter_SingleUser
     */
    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    /**
     * This method sets the credential
     *
     * @param string
     * @return InAdvant_Auth_Adapter_SingleUser
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        if ($this->_identity != self::$_username || $this->_credential != self::$_password)
        {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE,
                null, array('Authentication failure.'));
        }

        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,
            $this->_identity, array('Authentication sucessful.'));
    }
}
