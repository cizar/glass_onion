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
     * Appends an asset and all its dependencies
     *
     * @param string $id
     * @return Zend_View
     */
    public function asset()
    {
        $ids = func_get_args();

        $assets = Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getResource('assets');
        
        foreach ($ids as $id) {
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
                    case 'favicon':
                        $this->view->favicon($asset->href);
                        break;
                }
            }
        }

        return $this->view;
    }
}
