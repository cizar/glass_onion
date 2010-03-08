<?php

/**
 * @see Zend_View_HtmlFlash
 */
require_once 'Zend/View/Helper/HtmlFlash.php';

class GlassOnion_View_Helper_SonetticVideo extends Zend_View_HtmlFlash
{
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
