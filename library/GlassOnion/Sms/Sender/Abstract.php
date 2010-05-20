<?php

require_once('GlassOnion/Sms/Message.php');

abstract class GlassOnion_Sms_Sender_Abstract
{
	abstract function send(GlassOnion_Sms_Message $sms);
	
	public function createSms($number, $message, $originator)
	{
		$sms = new GlassOnion_Sms_Message();
		
		$sms->setNumber($number)
			->setMessage($message)
			->setOriginator($originator)
			->setSender($this);
			
		return $sms;
	}
}