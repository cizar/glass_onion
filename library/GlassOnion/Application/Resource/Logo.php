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
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Logo
    extends Zend_Application_Resource_ResourceAbstract
{
  /**
   * Defined by Zend_Application_Resource_Resource
   *
   * @return Zend_View_Helper_HeadMeta
   */
  public function init()
  {
    $bootstrap = $this->getBootstrap();

    if (!$bootstrap->hasResource('view')) {
      require_once 'Zend/Application/Resource/Exception.php';
      throw new Zend_Application_Resource_Exception('No view defined');
    }
    
    $headStyle = $bootstrap->bootstrap('view')
      ->getResource('view')->headStyle();

    $options = $this->getOptions();

    if (isset($options['url'])) {
      $options = array($options);
    }

    $style = '';

    foreach ($options as $logo) {
      if (!isset($logo['selector']) || !isset($logo['url'])) {
        require_once 'Zend/Application/Resource/Exception.php';
        throw new Zend_Application_Resource_Exception('Invalid arguments');
      }

      $props = array(
        'display' => 'inline-block',
        'overflow' => 'hidden',
        'text-indent' => '-9999px',
        'background-position' => 'center center',
        'background-repeat' => 'no-repeat',
        'background-size' => '100% auto',
        'background-image' => 'url(' . $logo['url'] . ')'
      );
      
      if (isset($logo['width'])) {
        $props['width'] = $logo['width'] . 'px';
      }

      if (isset($logo['height'])) {
        $props['height'] = $logo['height'] . 'px';
      }

      $style .= $logo['selector'] . '{';
      foreach ($props as $key => $value) {
        $style .= $key . ':' . $value . ';';
      }
      $style .= '}';
    }

    $headStyle->appendStyle($style);
    
    return $headStyle;
  }
}
