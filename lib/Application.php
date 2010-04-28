<?php

class Application
{
	
	private 
		$_config,
		$_components = array();
		
	
	/*
		constructor, configuration array as parameter
	*/
	public function __construct( $config )
	{
		$this->_config = $config;
	}
	
	/*
		returns configuration array
	*/
	public function getConfig()
	{
		return $this->_config;
	}
	
	/*
		creates new component or returns already instantiated component
		components: DataBase, Request, Session, Dispatcher
	*/
	public function getComponent( $key )
	{
		if( !isset( $this->_components[ $key ] ) )
		{
			$config = $this->getConfig();
			$config = ( isset( $config[ $key ] ) ) ? $config[ $key ] : array();
			$this->_components[ $key ] = new $key();
			$this->_components[ $key ]->init( $config );
		}
		return $this->_components[ $key ];
	}

	/*
		execute scripts, echo content
	*/
	public function dispatch()
	{
		$dispatcher = $this->getComponent( 'Dispatcher' );
		$dispatcher->executeDispatch();
		echo $dispatcher->getTemplate()->getContent();
	}
	
	/*
		returns application directory
	*/
	public function getAppDir()
	{
		return $this->_config[ 'appDir' ];
	}
	
	/*
		returns base url
	*/
	public function getBaseUrl()
	{
		return $this->_config[ 'baseUrl' ];
	}
	
	/*
		tests for controller name in /controllers/ dir
	*/
	public function hasController( $name )
	{
		$name = ucfirst( $name );
		return file_exists( $this->getAppDir() . '/controllers/' . $name . '.php' );
	}
	
}
