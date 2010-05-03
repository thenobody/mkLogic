<?php

/*
	application specific user session abstraction
*/
class UserSession extends BaseComponent
{

	private
		$_errors;

	public function getToken()
	{
		return $this->getSession()->get( "token", false );
	}
	
	public function setToken( $token = false )
	{
		$this->getSession()->set( "token", $token );
	}

}
