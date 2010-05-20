<?php

class GlassOnion_Sms_Message
{
	/**
     * the number or numbers to receive this sms
     *
     * @var string
     */
    protected $numbers = array();

    /**
     * the message of this sms
     *
     * @var string
     */
    protected $message = null;

    /**
     * name of the sender
     *
     * @var string
     */
    protected $originator = null;

    /**
     * the sms sender
     *
     * @var GlassOnion_Sms_Sender_Abstract
     */
    protected $sender = null;

	/**
	 *
	 */
	public function setNumber($number)
	{
		$this->numbers = (array)$number;
		return $this;
	}

	/**
	 *
	 */
	public function setNumbers($number)
	{
		$this->numbers = $number;
		return $this;
	}
	
	/**
	 *
	 */
	public function addNumber($number)
	{
		$this->numbers[] = $number;
		return $this;
	}
	
	/**
	 *
	 */
	public function getNumbers()
	{
		return $this->numbers;
	}
	
	/**
	 *
	 */
	public function setMessage($message)
	{
		$this->message = $message;
		return $this;
	}
	
	/**
	 *
	 */
	public function getMessage()
	{
		return $this->message;
	}
	
	/**
	 *
	 */
	public function setOriginator($originator)
	{
		$this->originator = $originator;
		return $this;
	}
	
	/**
	 *
	 */
	public function getOriginator()
	{
		return $this->originator;
	}
	
	/**
	 *
	 */
	public function setSender(GlassOnion_Sms_Sender_Abstract $sender)
	{
		$this->sender = $sender;
	}
	
	/**
	 *
	 */
	public function send()
	{
		return $this->sender->send($this);
	}
}