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
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_FlashMessages
    extends Zend_View_Helper_Abstract
    implements Iterator, Countable
{
    /**
     * @const string
     */
    const DEFAULT_STATUS = 'info';

    /**
     * @const string
     */
    const DEFAULT_TEMPLATE = '<div class="%s">%s</div>';

    /**
     * @var array
     */
    private $_messages = null;

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    private $_flashMessenger = null;

    /**
     * Whether or not auto-translation is enabled
     *
     * @var boolean
     */
    private $_translate = false;

    /**
     * Translation object
     *
     * @var Zend_Translate_Adapter
     */
    private $_translator;

    /**
     * @var string
     */
    private $_template;

    /**
     * @return GlassOnion_View_Helper_FlashMessages Provides a fluent interface
     */
    public function flashMessages()
    {
        if (null == $this->_messages) {
            $flashMessenger = $this->_getFlashMessenger();
            $this->_messages = $flashMessenger->getMessages();
            if ($flashMessenger->hasCurrentMessages()) {
                $this->_messages = array_merge($this->_messages,
                    $flashMessenger->getCurrentMessages());
                $flashMessenger->clearCurrentMessages();
            }            
        }
        return $this;
    }

    /**
     * Returns the current message.
     *
     * @access public
     * @return array
     */
    public function current()
    {
        return current($this->_messages);
    }

    /**
     * Returns the current message position.
     *
     * @access public
     * @return integer
     */
    public function key()
    {
        return key($this->_messages);
    }

    /**
     * Move the array pointer to the next message.
     *
     * @access public
     * @return void
     */
    public function next()
    {
        next($this->_messages);
    }

    /**
     * Reset the array.
     *
     * @access public
     * @return void
     */
    public function rewind()
    {
        reset($this->_messages);
    }

    /**
     * Checks if the current position is readable.
     *
     * @access public
     * @return boolean
     */
    public function valid()
    {
        return key($this->_messages) !== null;
    }

    /**
     * Returns the number of messages.
     *
     * @access public
     * @return integer
     */
    public function count()
    {
        return count($this->_messages);
    }

    /**
     * Sets a translation Adapter for translation
     *
     * @param  Zend_Translate|Zend_Translate_Adapter $translate
     * @return GlassOnion_View_Helper_FlashMessages Provides a fluent interface
     */
    public function setTranslator($translate)
    {
        if ($translate instanceof Zend_Translate_Adapter) {
            $this->_translator = $translate;
        } elseif ($translate instanceof Zend_Translate) {
            $this->_translator = $translate->getAdapter();
        } else {
            require_once 'Zend/View/Exception.php';
            $e = new Zend_View_Exception('You must set an instance of Zend_Translate or Zend_Translate_Adapter');
            $e->setView($this->view);
            throw $e;
        }
        return $this;
    }
    
    /**
     * Retrieve translation object
     *
     * If none is currently registered, attempts to pull it from the registry
     * using the key 'Zend_Translate'.
     *
     * @return Zend_Translate_Adapter|null
     */
    public function getTranslator()
    {
        if (null === $this->_translator) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $this->setTranslator(Zend_Registry::get('Zend_Translate'));
            }
        }
        return $this->_translator;
    }

    /**
     * Enables translation
     *
     * @return GlassOnion_View_Helper_FlashMessages Provides a fluent interface
     */
    public function enableTranslation()
    {
        $this->_translate = true;
        return $this;
    }

    /**
     * Disables translation
     *
     * @return GlassOnion_View_Helper_FlashMessages Provides a fluent interface
     */
    public function disableTranslation()
    {
        $this->_translate = false;
        return $this;
    }

    /**
     * Returns the string representation
     *
     * @return string
     */
    public function toString()
    {
        $output = '';
        foreach ($this->_messages as $message) {
            if (is_string($message)) {
                $message = array('status' => $this->getDefaultStatus(), 'text' => $message);
            }
            $output = $this->renderMessage($message);
        }
        return $output;
    }
    
    /**
     * Cast to string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Returns the default status for messages that have no status
     *
     * @return string
     */
    protected function getDefaultStatus()
    {
        return self::DEFAULT_STATUS;
    }

    /**
     * Returns an HTML string for a given message
     *
     * @return string
     */
    protected function renderMessage($message)
    {
        return sprintf($this->getTemplate(),
            $this->getClassForStatus($message['status']),
            $this->getTranslationForText($message['text'])
        );
    }

    /**
     * Returns the class for a given status
     *
     * @return string
     */
    protected function getClassForStatus($status)
    {
        return $status;
    }

    /**
     * Returns a translation for a given text if the translation is enabled
     *
     * @return string
     */
    protected function getTranslationForText($text)
    {
        if ($this->_translate && $translator = $this->getTranslator()) {
            return $translator->translate($text);
        }
        return $text;
    }

    /**
     * Returns the template
     *
     * @return string
     */
    protected function getTemplate()
    {
        if (null == $this->_template) {
            $this->_template = self::DEFAULT_TEMPLATE;
        }
        return $this->_template;
    }

    /**
     * Sets the template
     *
     * @return GlassOnion_View_Helper_FlashMessages Provides a fluent interface
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
        return $this;
    }

    /**
     * Returns the FlashMessenger instance.
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    private function _getFlashMessenger()
    {
        if (null === $this->_flashMessenger) {
            $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        }
        return $this->_flashMessenger;
    }
}