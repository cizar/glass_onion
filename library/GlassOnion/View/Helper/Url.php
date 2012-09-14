<?php

/**
 * @see Zend_View_Helper_Url
 */
require_once 'Zend/View/Helper/Url.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Url
    extends Zend_View_Helper_Url
{
    /**
     * Extends the Zend URL Helper response appending the query string
     *
     * @param array $urlOptions
     * @param mixed $name
     * @param boolean $reset
     * @param boolean $encode
     * @return string
     */
    public function url(array $urlOptions = array(), $name = null,
        $reset = false, $encode = true, $includeQueryString = true)
    {
        $url = parent::url($urlOptions, $name, $reset, $encode);
        return $includeQueryString && !isset($_SERVER['QUERY_STRING'])
            ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
    }
}
