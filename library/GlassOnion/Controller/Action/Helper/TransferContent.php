<?php

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Helper
 */
class GlassOnion_Controller_Action_Helper_TransferContent
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Start a file transfer
     *
     * @param string $content
     * @param string $contentType
     * @param string $filename
     * @param array $options
     * @return void
     */
    public function direct($content, $contentType = 'text/plain', $filename = 'unknown', $options = array())
    {
        $response = $this->getResponse();
        
        if (!$response->canSendHeaders()) {
            return false;
        }
        
        if (isset($options['disposition']) && 'inline' === $options['disposition']) {
            $disposition = 'inline';
        } else {
            $disposition = 'attachment';
        }
                    
        $response->setHttpResponseCode(200)
                 ->setHeader('Content-Type', $contentType, true)
                 ->setHeader('Content-Disposition',
                    $disposition . '; filename="' . $filename . '"', true)
                 ->setHeader('Content-Length', strlen($content), true)
                 ->setBody($content)
                 ->sendResponse();

        exit;
    }
}
