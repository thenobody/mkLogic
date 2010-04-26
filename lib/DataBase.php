<?php

/*
	Outlet ORM wrapper
*/
class DataBase extends BaseComponent
{
	
	private
		$_config,
		$_outlet;
	
	/*
		instantiate Outlet and create proxy objects for Models
	*/
	public function init( array $config )
	{
		$this->setConfig( $config );
		Outlet::init( $config );
		$this->_outlet = Outlet::getInstance();
		$this->getOutlet()->createProxies();
	}
	
	/*
		returns database configuration array
	*/
	public function getConfig()
	{
		return $this->_config;
	}
	
	/*
		set database configuration array
	*/
	public function setConfig( $config )
	{
		$this->_config = $config;
	}
	
	/*
		returns internal outlet connection object for Model manipulation
	*/
	public function getOutlet()
	{
		return $this->_outlet;
	}
	
}