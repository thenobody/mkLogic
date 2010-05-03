<?php

/*
	application specific base controller class
*/
class AppController extends Controller
{

	public function getApp()
	{
		return Core::getApp();
	}

	public function getUser()
	{
		return $this->getApp()->getComponent( 'UserSession' );
	}

	public function getOutlet()
	{
		return $this->getApp()->getComponent( 'DataBase' )->getOutlet();
	}
	
	public function getRequest()
	{
		return $this->getApp()->getComponent( 'Request' );
	}

}