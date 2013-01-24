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
     * Returns a time lapse in human readable format
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
                return $this->view->translate(($distanceInMinutes == 0) ? 'less than a minute' : '1 minute');
            } else {
                if ( $distanceInSeconds < 5 ) {
                    return $this->view->translate('less than %s seconds', 5);
                }
                if ( $distanceInSeconds < 10 ) {
                    return $this->view->translate('less than %s seconds', 10);
                }
                if ( $distanceInSeconds < 20 ) {
                    return $this->view->translate('less than %s seconds', 20);
                }
                if ( $distanceInSeconds < 40 ) {
                    return $this->view->translate('about half a minute');
                }
                if ( $distanceInSeconds < 60 ) {
                    return $this->view->translate('less than a minute');
                }

                return $this->view->translate('1 minute');
            }
        }
        if ( $distanceInMinutes < 45 ) {
            return $this->view->translate('%s minutes', $distanceInMinutes);
        }
        if ( $distanceInMinutes < 90 ) {
            return $this->view->translate('about 1 hour');
        }
        if ( $distanceInMinutes < 1440 ) {
            return $this->view->translate('about %s hours', round(floatval($distanceInMinutes) / 60.0));
        }
        if ( $distanceInMinutes < 2880 ) {
            return $this->view->translate('1 day');
        }
        if ( $distanceInMinutes < 43200 ) {
            return $this->view->translate('about %s days', round(floatval($distanceInMinutes) / 1440));
        }
        if ( $distanceInMinutes < 86400 ) {
            return $this->view->translate('about 1 month');
        }
        if ( $distanceInMinutes < 525600 ) {
            return $this->view->translate('%s months', round(floatval($distanceInMinutes) / 43200));
        }
        if ( $distanceInMinutes < 1051199 ) {
            return $this->view->translate('about 1 year');
        }

        return $this->view->translate('over %s years', array(round(floatval($distanceInMinutes) / 525600)));
    }
}
