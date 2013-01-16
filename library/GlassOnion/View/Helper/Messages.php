<?php

/**
 * @see GlassOnion_View_Helper_Messages
 */
require_once 'GlassOnion/View/Helper/Messages.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Messages
    extends GlassOnion_View_Helper_FlashMessenger
{
    /**
     * @const string
     */
    const DEFAULT_TEMPLATE = '<div class="%s">%s</div>';

    /**
     * @const string
     */
    const DEFAULT_STATUS = 'info';

    /**
     * Returns the Flash Messenger object.
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function messages($status = self::DEFAULT_STATUS, $template = self::DEFAULT_TEMPLATE)
    {
        if (null == $status) {
            $status = self::DEFAULT_STATUS;
        }

        if (null == $template) {
            $template = self::DEFAULT_TEMPLATE;
        }

        $flashMessenger = $this->flashMessenger();

        $messages = $flashMessenger->getMessages();

        if ($flashMessenger->hasCurrentMessages()) {
            $messages = array_merge($messages, $flashMessenger->getCurrentMessages());
            $flashMessenger->clearCurrentMessages();
        }

        $output ='';

        foreach ($messages as $message) {
            if (is_string($message)) {
                $message = array('status' => $status, 'message' => $message);
            }
            $output .= sprintf($template, $message['status'], $message['message']);
        }

        return $output;
    }
}