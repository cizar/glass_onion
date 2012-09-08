<?php

/**
 * @category   GlassOnion
 * @package    GlassOnion_Sms
 */
class GlassOnion_Sms_Message
{
    /**
     * The number or numbers to receive this SMS
     *
     * @var string
     */
    protected $numbers = array();

    /**
     * The message of this SMS
     *
     * @var string
     */
    protected $message = null;

    /**
     * The name of the originator
     *
     * @var string
     */
    protected $originator = null;

    /**
     * The SMS concrete sender
     *
     * @var GlassOnion_Sms_Sender_Abstract
     */
    protected $sender = null;

    /**
     * Set one SMS receiver
     *
     * @return GlassOnion_Sms_Message Provides a fluent interface
     */
    public function setNumber($number)
    {
        $this->numbers = (array)$number;
        return $this;
    }

    /**
     * Sets multiple SMS receivers
     *
     * @return GlassOnion_Sms_Message Provides a fluent interface
     */
    public function setNumbers($number)
    {
        $this->numbers = $number;
        return $this;
    }
    
    /**
     * Append one SMS receiver
     *
     * @return GlassOnion_Sms_Message Provides a fluent interface
     */
    public function addNumber($number)
    {
        $this->numbers[] = $number;
        return $this;
    }
    
    /**
     * Gets all sms receivers
     *
     * @return array
     */
    public function getNumbers()
    {
        return $this->numbers;
    }
    
    /**
     * Set the SMS message
     *
     * @return GlassOnion_Sms_Message Provides a fluent interface
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    
    /**
     * Gets the SMS message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Sets the SMS originator
     *
     * @return GlassOnion_Sms_Message Provides a fluent interface
     */
    public function setOriginator($originator)
    {
        $this->originator = $originator;
        return $this;
    }
    
    /**
     * Gets the SMS originator
     *
     * @return mixed
     */
    public function getOriginator()
    {
        return $this->originator;
    }
    
    /**
     * Sets the SMS concrete sender
     *
     * @return GlassOnion_Sms_Message Provides a fluent interface
     */
    public function setSender(GlassOnion_Sms_Sender_Abstract $sender)
    {
        $this->sender = $sender;
        return $this;
    }
    
    /**
     * Send the SMS throw the concrete sender
     *
     * @return GlassOnion_Sms_Message Provides a fluent interface
     */
    public function send()
    {
        $this->sender->send($this);
        return $this;
    }
}
