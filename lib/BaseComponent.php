<?php

class BaseComponent
{

	/*
		initialize component with config
	*/
	public function init( $config )
	{
		//
	}
	
	/*
		returns database Outlet handler
	*/
	public function getDB()
	{
		return Core::getApp()->getComponent( 'DataBase' )->getOutlet();
	}

	/*
		returns session management component
	*/
	public function getSession()
	{
		return Core::getApp()->getComponent( 'Session' );
	}

	/*
		returns request handler
	*/
	public function getRequest()
	{
		return Core::getApp()->getComponent( 'Request' );
	}

	/*
		reuturns dispatcher component
	*/
	public function getDispatcher()
	{
		return Core::getApp()->getComponent( 'Dispatcher' );
	}
	
	/*
		returns global application
	*/
	public function getApplication()
	{
		return Core::getApp();
	}

}