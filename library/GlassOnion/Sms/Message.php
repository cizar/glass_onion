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