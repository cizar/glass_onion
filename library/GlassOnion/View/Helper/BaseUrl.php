<?php

class GlassOnion_View_Helper_BaseUrl extends Zend_View_Helper_Abstract 
{
    public function baseUrl($file = null, $absolute = false)
    {
    	$frontController = Zend_Controller_Front::getInstance();
    	
    	$baseUrl = $frontController->getBaseUrl();

        if ($absolute)
        { 
            $baseUrl = 'http://' . $frontController->getRequest()->getHttpHost() . $baseUrl; 
        }
        
        $file = is_null($file) || empty($file) ? '' : '/' . ltrim($file, '/'); 
        
        return $baseUrl . $file;
    }
}
