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
 * @see GlassOnion_Sms_Sender_Abstract
 */
require_once 'GlassOnion/Sms/Sender/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Sms
 */
class GlassOnion_Sms_Sender_Cyberneticos extends GlassOnion_Sms_Sender_Abstract
{
    private $_config;
    
    private $_serviceUrl = 'http://websmsapi.cyberneticos.com/clients/cyber001/';
    
    public function __construct($config)
    {
        $this->_config = $config;
    }
    
    public function send(GlassOnion_Sms_Message $sms)
    {
        $result = $this->_postToService(
            $this->_smsToXml($sms),
            $this->_config['username'],
            $this->_config['password']
        );
        
        $errno = (int) $result->code;
        
        if ($errno)
        {
            require_once('GlassOnion/Sms/Sender/Exception.php');
            throw new GlassOnion_Sms_Sender_Exception($result->message, $errno);
        }
    }
    
    private function _postToService($xml, $username = null, $password = null)
    {
        // Initialize the cURL instance
        $ch = curl_init(); 

        // Set the request URL
        curl_setopt($ch, CURLOPT_URL, $this->_serviceUrl); 

        // Must return the result
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set the POST content
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'XmlData=' . $xml); 

        // Set the request time out
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        // Authentication options
        if ($username) {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        }

        // Execute the request and close
        $result = curl_exec($ch);
        
        // Check for cURL errors
        if ($errno = curl_errno($ch))
        {
            require_once('GlassOnion/Sms/Sender/Exception.php');
            throw new GlassOnion_Sms_Sender_Exception('cURL: ' . curl_error($ch), $errno);
        }

        // Check for HTTP status code
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($http_code != 200)
        {
            require_once('GlassOnion/Sms/Sender/Exception.php');
            throw new GlassOnion_Sms_Sender_Exception('HTTP/' . $http_code, $http_code);
        }

        // Close the cURL instance
        curl_close($ch); 

        // Return the result
        return simplexml_load_string($result);
    }
    
    private function _smsToXml($sms)
    {
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
            . "<sms>\n"
            . "  <recipient>\n";
        
        foreach ($sms->getNumbers() as $number)
        {
            $xml .= "    <msisdn>" . $number . "</msisdn>\n";
        }
        
        $xml .= "  </recipient>\n"
            . "  <message>" . $sms->getMessage() . "</message>\n"
            . "  <tpoa>" . $sms->getOriginator() . "</tpoa>\n"
            . "</sms>\n";

        return $xml;
    }
}
