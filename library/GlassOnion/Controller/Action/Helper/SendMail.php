<?php

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Helper
 */
class GlassOnion_Controller_Action_Helper_SendMail
    extends Zend_Controller_Action_Helper_Abstract
{
    public function direct($template, $recipient, $vars = null)
    {
        $this->sendMail($template, $recipient, $vars);
    }

    public function sendMail($template, $recipient, $vars = null)
    {
        list($email, $name) = is_array($recipient)
            ? $recipient : array($recipient, null);

        $view = new Zend_View();
        $view->setEncoding('utf-8');
        $view->setBasePath(APPLICATION_PATH . '/mails');

        if (!is_null($vars)) {
            $view->assign($vars);
        }

        $mail = new Zend_Mail('utf-8');
        $mail->addTo($email, $name);
        $mail->setSubject($view->render($template . '/subject.phtml'));
        $mail->setBodyHtml($view->render($template . '/body-html.phtml'));
        $mail->setBodyText($view->render($template . '/body-text.phtml'));
        $mail->send();
    }
}