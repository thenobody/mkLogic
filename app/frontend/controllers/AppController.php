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
		return Core::getApp()->getUser();
	}
	
	public function getUserModel()
	{
		return $this->getOutlet()->load( 'User', $this->getUser()->getId() );
	}
	
	public function getOutlet()
	{
		return Core::getApp()->getComponent( 'DataBase' )->getOutlet();
	}
	
	public function getRequest()
	{
		return Core::getApp()->getComponent( 'Request' );
	}

}