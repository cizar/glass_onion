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
 * @see Zend_View_HtmlFlash
 */
require_once 'Zend/View/Helper/HtmlFlash.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_SonetticVideo extends Zend_View_HtmlFlash
{
    /**
     * @return string
     */
    public function sonetticVideo($movie, array $attribs = array(), array $params = array(), $content = null)
    {
        /*
         * FIXME:
         * 
         *   Este helper debe retornar el <object> del player de Sonettic
         * 
         *   <object width="600" height="360"  id="sonetticFLVPlayer"
         *       classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
         *       codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0">
         *       
         *       <param name="movie" value="cinemaplayer.swf"/>
         *       <param name="allowFullScreen" value="true"/>
         *       <param name="allowScriptAccess" value="sameDomain" />
         *       <param name="bgcolor" value="#161616"/>
         *       <param name="flashvars" value="poster=thumb.png&content=trailer.flv"/>
         *       
         *       <embed src="cinemaplayer.swf" name="sonetticFLVPlayer"
         *       type="application/x-shockwave-flash" width="600" height="360"
         *       bgcolor="#161616" allowFullScreen="true"
         *       allowScriptAccess="sameDomain"
         *       flashvars="poster=thumb.png&content=trailer.flv"> </embed>
         *       
         *   </object>
         */

        $params = array_merge(
            array('movie' => $movie, 'quality' => 'high'), $params);

        return $this->htmlFlash('cinemaplayer.swf', $attribs, $params, $content);
    }
}