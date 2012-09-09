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
class GlassOnion_View_Helper_Asset
    extends Zend_View_Helper_Abstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @param string $href
     * @return Zend_View
     */
    public function asset($id)
    {
        $assets = Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getResource('assets');
        
        foreach ($assets->getRequiredAssets($id) as $asset) {
            switch ($asset->getClass()) {
                case 'script':
                        $this->view->script($asset->src,
                            $asset->getProperty('type', 'text/javascript'));
                    break;
                case 'stylesheet':
                        $this->view->stylesheet($asset->href,
                            $asset->getProperty('media', 'screen'));
                    break;
            }
        }

        return $this->view;
    }
}
