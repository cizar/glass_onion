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
class GlassOnion_View_Helper_TimeAgo extends Zend_View_Helper_Abstract
{
	/**
	 * Returns the identity of the current session
	 *
	 * @param string $field
	 * @return string
	 */
	public function timeAgo($fromTime, $toTime = null, $showLessThanAMinute = false)
	{
		$toTime = is_null($toTime) ? time() : strtotime($toTime);
		$fromTime = strtotime($fromTime);

		$distanceInSeconds = round(abs($toTime - $fromTime));
		$distanceInMinutes = round($distanceInSeconds / 60);

		if ( $distanceInMinutes <= 1 ) {
			if ( !$showLessThanAMinute ) {
				return ($distanceInMinutes == 0) ? 'less than a minute' : '1 minute';
			} else {
				if ( $distanceInSeconds < 5 ) {
					return 'less than 5 seconds';
				}
				if ( $distanceInSeconds < 10 ) {
					return 'less than 10 seconds';
				}
				if ( $distanceInSeconds < 20 ) {
					return 'less than 20 seconds';
				}
				if ( $distanceInSeconds < 40 ) {
					return 'about half a minute';
				}
				if ( $distanceInSeconds < 60 ) {
					return 'less than a minute';
				}

				return '1 minute';
			}
		}
		if ( $distanceInMinutes < 45 ) {
			return $distanceInMinutes . ' minutes';
		}
		if ( $distanceInMinutes < 90 ) {
			return 'about 1 hour';
		}
		if ( $distanceInMinutes < 1440 ) {
			return 'about ' . round(floatval($distanceInMinutes) / 60.0) . ' hours';
		}
		if ( $distanceInMinutes < 2880 ) {
			return '1 day';
		}
		if ( $distanceInMinutes < 43200 ) {
			return 'about ' . round(floatval($distanceInMinutes) / 1440) . ' days';
		}
		if ( $distanceInMinutes < 86400 ) {
			return 'about 1 month';
		}
		if ( $distanceInMinutes < 525600 ) {
			return round(floatval($distanceInMinutes) / 43200) . ' months';
		}
		if ( $distanceInMinutes < 1051199 ) {
			return 'about 1 year';
		}

		return 'over ' . round(floatval($distanceInMinutes) / 525600) . ' years';
	}
}
