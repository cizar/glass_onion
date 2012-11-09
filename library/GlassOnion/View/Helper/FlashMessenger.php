<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_FlashMessenger
    extends Zend_View_Helper_Abstract
{
	/**
	 * @const string
	 */
	const DEFAULT_TEMPLATE = '<div class="message %s">%s</div>';

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    private $_flashMessenger = null;

    /**
     * Display Flash Messages.
     *
     * @return string
     */
    public function flashMessenger($template = null)
    {
    	if (null === $template) {
	    	$template = self::DEFAULT_TEMPLATE;
    	}

        $flashMessenger = $this->_getFlashMessenger();

        $messages = $flashMessenger->getMessages();

        if (!$messages) {
            $messages = $flashMessenger->getCurrentMessages();
            $flashMessenger->clearCurrentMessages();
        }

        $output ='';

        foreach ($messages as $message) {
            $output .= sprintf($template, $message->status, $message->message);
        }

        return $output;
    }

    /**
     * Lazily fetches FlashMessenger Instance.
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function _getFlashMessenger()
    {
        if (null === $this->_flashMessenger) {
            $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        }
        return $this->_flashMessenger;
    }
}