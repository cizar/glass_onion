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
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Helper
 */
class GlassOnion_Controller_Action_Helper_Log
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Log
     */
    private $_logger = null;
    
    /**
     * Returns the logger and logs a message if it's defined
     *
     * @param string|Exception $message
     * @param int $priority
     * @return Zend_Log
     */
    public function direct($message = null, $priority = Zend_Log::INFO)
    {
        $logger = $this->getLogger();
        if ($message instanceof Exception) {
            $message = get_class($message) . ' "' . $message->getMessage() . '"';
        }
        if (null !== $message) {
            $request = $this->getRequest();
            $trace = debug_backtrace();
            $method = isset($trace[4]) ? $trace[4]['function'] : 'unknown';

            $logger->log(sprintf('/%s/%s/%s::%s() - %s',
                $request->getModuleName(),
                $request->getControllerName(),
                $request->getActionName(),
                $method,
                $message
            ), $priority);
        }
        return $logger;
    }

    /**
     * Returns the logger
     *
     * @return Zend_Log
     */
    public function getLogger()
    {
        if (null !== $this->_logger) {
            return $this->_logger;
        }
        
        $bootstrap = $this->getActionController()
            ->getInvokeArg('bootstrap');

        if ($bootstrap->hasResource('log')) {
            return $bootstrap->getResource('log');
        }

        /**
         * @see Zend_Log
         */
        require_once 'Zend/Log.php';
        $this->_logger = new Zend_Log(new Zend_Log_Writer_Null());

        return $this->_logger;
    }
}
