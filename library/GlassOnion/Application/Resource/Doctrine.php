<?php

class GlassOnion_Application_Resource_Doctrine extends  Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
	$config = $this->getOptions();

	require_once 'Doctrine.php';

	Zend_Loader_Autoloader::getInstance()
		->pushAutoloader(array('Doctrine_Core', 'autoload'), 'Doctrine');

	$manager = Doctrine_Manager::getInstance();

	$manager->setAttribute(
		Doctrine::ATTR_MODEL_LOADING, 
		Doctrine::MODEL_LOADING_CONSERVATIVE);

	$manager->setAttribute(
		Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES,
		TRUE);

	$manager->setAttribute(
		Doctrine::ATTR_VALIDATE,
		Doctrine::VALIDATE_ALL);

	$manager->setAttribute(
		Doctrine_Core::ATTR_USE_DQL_CALLBACKS,
		TRUE);

	$manager->setAttribute(
		Doctrine::ATTR_QUOTE_IDENTIFIER,
		TRUE);

	$manager->openConnection($config['connection_string']);

	return $manager;
    }
}
