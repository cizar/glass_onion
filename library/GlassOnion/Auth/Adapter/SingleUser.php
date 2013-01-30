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
 * @see Zend_Auth_Result
 */
require_once 'Zend/Auth/Result.php';

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Auth
 */
class GlassOnion_Auth_Adapter_SingleUser
    implements Zend_Auth_Adapter_Interface
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
     *
     * @return InAdvant_Auth_Adapter_SingleUser Provides a fluent interface
     */
    public static function configure($username, $password = null)
    {
        if (is_array($username)) {
            self::$_username = $username['username'];
            self::$_password = $username['password'];
        }
        else {
            self::$_username = $username;
            self::$_password = $password;
        }
        return $this;
    }

    /**
     * This method sets the identity
     *
     * @param string
     * @return InAdvant_Auth_Adapter_SingleUser Provides a fluent interface
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
     * @return InAdvant_Auth_Adapter_SingleUser Provides a fluent interface
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
        if ($this->_identity != self::$_username || $this->_credential != self::$_password) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE,
                null, array('Authentication failure.'));
        }

        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,
            $this->_identity, array('Authentication sucessful.'));
    }
}
