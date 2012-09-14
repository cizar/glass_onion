<?php

/**
 * @see Zend_Controller_Front
 */
require_once 'Zend/Controller/Front.php';

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Fop extends Zend_View_Helper_Abstract
{
    /**
     * Renders a PDF, disable the layout and set response header
     *
     * @return string
     */
    public function fop($xml, $xslFilename, $charset = 'UTF-8', $keepLayouts = false)
    {
        $fop = Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getResource('fop');

        $pdf = $fop->setXslFilename($xslFilename)
            ->setXml($xml)
            ->execute()
            ->getPdf();

        if (!$keepLayouts) {
            $layout = Zend_Layout::getMvcInstance();
            if ($layout instanceof Zend_Layout) {
                $layout->disableLayout();
            }
        }

        $contentType = 'application/pdf; charset=' . $charset;

        Zend_Controller_Front::getInstance()->getResponse()
            ->setHeader('Content-Type', $contentType)
            ->setHeader('Cache-Control', 'no-cache');

        return $pdf;
    }
}

