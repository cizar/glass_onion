<?php

require_once 'GlassOnion/Sms/Sender/Abstract.php';

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